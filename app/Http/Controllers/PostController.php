<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Like;
use App\Models\Share;
use App\Models\User;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;



class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('query');
    
        if (!empty($query)) {
            $hashtags = explode('#', $query);
            $hashtags = array_map('trim', $hashtags);
            $hashtags = array_filter($hashtags);
    
            $posts = Post::where(function ($queryBuilder) use ($hashtags) {
                foreach ($hashtags as $hashtag) {
                    $queryBuilder->whereHas('user', function ($q) use ($hashtag) {
                        $q->whereRaw("BINARY name LIKE ?", ['%' . $hashtag . '%']);
                    })->orWhere(function ($q) use ($hashtag) {
                        $q->whereRaw("BINARY Temat LIKE ?", ['%' . $hashtag . '%']);
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
        return view("posts.create");
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

    return redirect()->route('users.posts', ['user' => $post->user_id])->with('success', 'Post został pomyślnie zaktualizowany.');
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

        return redirect()->route('users.posts', ['user' => $user_id])->with('success', 'Post został pomyślnie dodany.'); 
       }



       public function likePost($postId): JsonResponse
{
    $user = Auth::user();
    
    if (!$user) {
        return response()->json(['status' => 'error', 'message' => 'User not authenticated'], 401);
    }

    $post = Post::find($postId);

    if (!$post) {
        return response()->json(['status' => 'error', 'message' => 'Post not found'], 404);
    }

    try {
        $existingLike = $user->likes()->where('post_id', $postId)->exists();

        if ($existingLike) {
            $user->likes()->where('post_id', $postId)->delete();
        } else {
            $like = new Like(['post_id' => $postId]);
            $user->likes()->save($like);
        }

        $likesCount = $post->likes()->count();

        return response()->json([
            'status' => 'success',
            'likesCount' => $likesCount,
        ]);
    } catch (\Exception $e) {
        \Log::error('Error in likePost: ' . $e->getMessage());
        return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
    }
}

public function __construct()
    {
        $this->middleware('auth:sanctum')->only('likePost');
    }

public function sherePost($postId): JsonResponse
{
    $user = Auth::user();



    if (!Auth::check()) {
        return response()->json(['status' => 'error', 'message' => 'User not authenticated'], 401);
    }
    if (!$user) {
        return response()->json(['status' => 'error', 'message' => 'User not authenticated'], 401);
    }

    $post = Post::find($postId);

    if (!$post) {
        return response()->json(['status' => 'error', 'message' => 'Post not found'], 404);
    }

    $existingShere = $user->sheres()->where('post_id', $postId)->exists();

    try {
        if ($existingShere) {
            $user->sheres()->where('post_id', $postId)->delete();
        } else {
            $shere = new Share(['post_id' => $postId]);
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

            Session::flash('success', 'Udało się usunąć post.');

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            \Log::error($e);

            if ($post->imageExists()) {
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Wystąpił błąd!'
            ])->setStatusCode(500);
        }
    }


    return response()->json([
        'status' => 'error',
        'message' => 'Brak autoryzacji.'
    ])->setStatusCode(401);
}


}
