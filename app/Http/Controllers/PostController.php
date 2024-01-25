<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
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
                        $q->where('name', 'like', '%'.$hashtag.'%');
                    })->orWhere(function ($q) use ($hashtag) {
                        $q->where('topic', 'like', '%'.$hashtag.'%');
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

    public function update(StorePostRequest $request, Post $post)
    {

        $imagePath = null;

        if ($request->has('remove_image') && $request->input('remove_image') == true) {
            if ($post->image_path) {
                Storage::disk('public')->delete($post->image_path);
                $imagePath = null;

            }
            $post->update(['image_path' => null]);
        } elseif ($request->hasFile('image')) {
            if ($post->image_path) {
                Storage::disk('public')->delete($post->image_path);

            }
            $imagePath = $request->file('image')->store('images', 'public');
        }
        $post->update([
            'topic' => $request->input('topic'), 'image_path' => $imagePath,
        ]);

        return redirect()->route('users.posts', ['user' => $post->user_id])->with('success', 'Post add.');
    }

    public function store(StorePostRequest $request)
    {

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
            $existingLike = $user->likes()->where('likable_id', $post->id)->where('likable_type', Post::class)->exists();

            if ($existingLike) {
                $user->likes()->where('likable_id', $post->id)->where('likable_type', Post::class)->delete();
            } else {
                $like = new Like(['likable_id' => $post->id, 'likable_type' => Post::class]);
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
}
