<?php

namespace App\Http\Controllers;

use App\Http\Middleware\AdminOrAuthorMiddleware;
use App\Models\Like;
use App\Models\Post;
use App\Models\Share;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('query');

        if (! empty($query)) {
            $hashtags = explode('#', $query);
            $hashtags = array_map('trim', $hashtags);
            $hashtags = array_filter($hashtags);

            $posts = Post::where(function ($queryBuilder) use ($hashtags) {
                foreach ($hashtags as $hashtag) {
                    $queryBuilder->whereHas('user', function ($q) use ($hashtag) {
                        $q->whereRaw('BINARY name LIKE ?', ['%'.$hashtag.'%']);
                    })->orWhere(function ($q) use ($hashtag) {
                        $q->whereRaw('BINARY Temat LIKE ?', ['%'.$hashtag.'%']);
                    });
                }
            })
                ->orderBy('created_at', 'desc')
                ->get();

            if ($posts->isEmpty()) {
                $posts = Post::orderBy('created_at', 'desc')->get();
            }
        } else {
            $posts = Post::orderBy('created_at', 'desc')->get();
        }

        return view('posts.index', compact('posts'));
    }

    public function create(): View
    {
        return view('posts.create');
    }

    public function show($id)
    {
        $post = Post::findOrFail($id);

        return view('posts.show', compact('post'));
    }

    public function edit(Post $post)
    {
        $this->authorize('edit', $post);

        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $request->validate([
            'Temat' => 'required|string',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $post->update([
            'Temat' => $request->input('Temat'),
        ]);

        if ($request->has('remove_image') && $request->input('remove_image') == 'on') {
            if ($post->image_path) {
                Storage::disk('public')->delete($post->image_path);
            }
            $post->update(['image_path' => null]);
        } elseif ($request->hasFile('image')) {
            if ($post->image_path) {
                Storage::disk('public')->delete($post->image_path);
            }
            $imagePath = $request->file('image')->store('images', 'public');
            $post->update(['image_path' => $imagePath]);
        }

        return redirect()->route('users.posts', ['user' => $post->user_id])->with('success', 'Post add.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'Temat' => 'required|string',
            'image' => 'image|mimes:jpeg,png,jpg,gif,mp4|max:2048',
        ]);

        $user_id = Auth::id();
        $request->merge(['user_id' => $user_id]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
            $request->merge(['image_path' => $imagePath]);
        }

        Post::create($request->all());

        return redirect()->route('users.posts', ['user' => $user_id])->with('success', 'Post add.');
    }

    public function likePost(Post $post): JsonResponse
    {
        $user = Auth::user();

        try {
            $existingLike = $user->likes()->where('post_id', $post->id)->exists();

            if ($existingLike) {
                $user->likes()->where('post_id', $post->id)->delete();
            } else {
                $like = new Like(['post_id' => $post->id]);
                $user->likes()->save($like);
            }

            $likesCount = $post->likes()->count();

            return response()->json([
                'status' => 'success',
                'likesCount' => $likesCount,
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in likePost: '.$e->getMessage());

            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function sherePost(Post $post): JsonResponse
    {
        $user = Auth::user();

        if (! $post) {
            return response()->json(['status' => 'error', 'message' => 'Post not found'], 404);
        }

        $existingShere = $user->sheres()->where('post_id', $post->id)->exists();

        try {
            if ($existingShere) {
                $user->sheres()->where('post_id', $post->id)->delete();
            } else {
                $shere = new Share(['post_id' => $post->id]);
                $user->sheres()->save($shere);
            }

            $sheresCount = $post->sheresCount();

            return response()->json([
                'status' => 'success',
                'sheresCount' => $sheresCount,
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'An error occurred'], 500);
        }
    }

    public function destroy(Post $post): JsonResponse
    {
        $user = Auth::user();

        if ($user && ($user->isAdmin() || $user->id === $post->user_id)) {
            try {

                if ($post->imageExists()) {
                    Storage::disk('public')->delete($post->image_path);
                }

                $post->likes()->delete();
                $post->sheres()->delete();
                $commentsWithLikes = $post->comments()->whereHas('likes')->get();

                foreach ($commentsWithLikes as $comment) {
                    $comment->likes()->delete();
                }

                $post->comments()->delete();
                $post->delete();

                Session::flash('success', 'Post destroy.');

                return response()->json(['status' => 'success']);
            } catch (\Exception $e) {
                \Log::error($e);

                return response()->json([
                    'status' => 'error',
                    'message' => 'Error!',
                ])->setStatusCode(500);
            }
        }

    }

    public function __construct()
    {
        $this->middleware(AdminOrAuthorMiddleware::class)->only('destroy');
    }
}
