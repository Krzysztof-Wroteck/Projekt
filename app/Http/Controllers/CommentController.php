<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function index(Post $post)
    {
        return view('posts.comments.index', compact('post'));
    }


    public function show(Post $post)
    {
        return view('posts.comments.page', compact('post'));
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

        $post->comments()->create($commentData);

        return redirect()->route('posts.index')->with('success', 'Komentarz został dodany.');
    }
}
