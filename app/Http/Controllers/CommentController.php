<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Comment;
use Illuminate\Support\Facades\Session; 
use Illuminate\Http\JsonResponse;

class CommentController extends Controller
{
    public function index(Post $post)
    {
        return view('posts.comments.index', compact('post'));
    }


    

    public function store(Request $request, Post $post)
    {
        $request->validate([
            'temat' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        $user_id = Auth::id();
    
        $commentData = [
            'temat' => $request->input('temat'),
            'user_id' => $user_id,
        ];
    
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('comments', 'public');
            $commentData['image_path'] = $imagePath;
        }
    
        $comment = $post->comments()->create($commentData);
    
        return redirect()->route('comments.index', $post->id)->with('success', 'Komentarz został dodany.');
    }
    
    
    public function edit(Comment $comment)
    {
        $this->authorize('edit', $comment);
    
        return view('posts.comments.edit', compact('comment'));
    }

public function update(Request $request, Comment $comment)
{
    $request->validate([
        'temat' => 'required|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $user_id = Auth::id();

    $commentData = [
        'temat' => $request->input('temat'),
        'user_id' => $user_id,
    ];

    if ($request->has('remove_image') && $comment->imageExists()) {
        Storage::disk('public')->delete($comment->image_path);
        $commentData['image_path'] = null;
    }

    if ($request->hasFile('image')) {
        if ($comment->imageExists()) {
            Storage::disk('public')->delete($comment->image_path);
        }

        $imagePath = $request->file('image')->store('comments', 'public');
        $commentData['image_path'] = $imagePath;
    }

    $post = $comment->post;

    $comment->update($commentData);

    return redirect()->route('comments.index', $post->id)->with('success', 'Komentarz został zaktualizowany.');
}


public function destroy(Post $post, Comment $comment): JsonResponse{


    $user = Auth::user();

    if ($user && ($user->isAdmin() || $user->id === $comment->user_id)) {
    try {
        if ($comment->imageExists()) {
            Storage::disk('public')->delete($comment->image_path);
        }

        $comment->likes()->delete();

        $comment->delete();

        Session::flash('success', 'Udało się usunąć komentarz.');

        return response()->json([
            'status' => 'success'
        ]);
    } catch (\Exception $e) {
        \Log::error($e);

        if ($comment->imageExists()) {
            \Log::info('Error occurred. Not deleting image from storage.');
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

public function like($commentId)
{
    $comment = Comment::findOrFail($commentId);
    $user = Auth::user();

    if ($user->likes()->where('comment_id', $comment->id)->exists()) {
        $user->likes()->where('comment_id', $comment->id)->delete();
    } else {
        $user->likes()->create(['comment_id' => $comment->id]);
    }

    return redirect()->back();
}

}
