<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

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

        return redirect()->route('comments.index', $post->id)->with('success', 'Comment add.');
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

            $commentData['image_path'] = $request->file('image')->store('comments', 'public');
        }

        $post = $comment->post;

        $comment->update($commentData);

        return redirect()->route('comments.index', $post->id)->with('success', ' Comment update.');
    }

    public function destroy(Post $post, Comment $comment): JsonResponse
    {

        $user = Auth::user();

        try {
            if ($comment->imageExists()) {
                Storage::disk('public')->delete($comment->image_path);
            }

            $comment->likes()->delete();

            $comment->delete();

            Session::flash('success', 'Comment destroy.');

            return response()->json([
                'status' => 'success',
            ]);
        } catch (\Exception $e) {
            \Log::error($e);

            return response()->json([
                'status' => 'error',
                'message' => 'Error!',
            ])->setStatusCode(500);
        }

    }

    public function like(Post $post, Comment $comment): JsonResponse
    {
        if (! $comment) {
            return response()->json(['status' => 'error', 'message' => 'Comment not found'], 404);
        }

        $user = Auth::user();

        try {
            $existingLike = $user->likes()->where('likable_id', $comment->id)->where('likable_type', Comment::class)->exists();

            if ($existingLike) {
                $user->likes()->where('likable_id', $comment->id)->where('likable_type', Comment::class)->delete();
            } else {
                $like = new Like(['likable_id' => $comment->id, 'likable_type' => Comment::class]);
                $user->likes()->save($like);
            }

            $likesCount = $comment->likes()->count();

            return response()->json([
                'status' => 'success',
                'likesCount' => $likesCount,
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in like: '.$e->getMessage());

            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
