<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Like;
use App\Models\Share;


use Illuminate\Support\Facades\Session;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index(): View
    {
        $posts = Post::all();
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
    return view('posts.edit', compact('post'));
}


public function update(Request $request, Post $post)
{
    $request->validate([
        'Temat' => 'required|string',
        'image' => 'image|mimes:jpeg,png,jpg,gif,mp4|max:2048',
    ]);

    $post->update([
        'Temat' => $request->input('Temat'),
    ]);

    if ($request->has('remove_image') && $request->input('remove_image') == 'on') {
        Storage::disk('public')->delete($post->image_path);
        $post->update(['image_path' => null]);
    } elseif ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('images', 'public');
        Storage::disk('public')->delete($post->image_path); 
        $post->update(['image_path' => $imagePath]);
    }

    return redirect()->route('posts.index')->with('success', 'Post został pomyślnie zaktualizowany.');
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

        return redirect()->route('posts.index')->with('success', 'Post został pomyślnie dodany.');
    }



    public function likePost($postId)
{
    $user = Auth::user();

    if ($user->likes()->where('post_id', $postId)->exists()) {

        $user->likes()->where('post_id', $postId)->delete();

    } else {

        $like = new Like(['post_id' => $postId]);

        $user->likes()->save($like);

    }

    $likesCount = Post::find($postId)->likesCount();

    return redirect()->route('posts.index')->with('success', 'Like został pomyślnie dodany.');


}




public function sherePost($postId)
{
    $user = Auth::user();

    if ($user->sheres()->where('post_id', $postId)->exists()) {

        $user->sheres()->where('post_id', $postId)->delete();

    } else {

        $share = new Share(['post_id' => $postId]);

        $user->sheres()->save($share);

    }

    $sheresCount = Post::find($postId)->sheresCount();

    return redirect()->route('posts.index')->with('success', 'sheres został pomyślnie dodany.');


}

public function destroy(Post $post): JsonResponse
{
    try {
        if ($post->imageExists()) {
            Storage::disk('public')->delete($post->image_path);
        }

        $post->likes()->delete();

        $post->sheres()->delete();

        $post->delete();

        Session::flash('success', 'Udało się usunąć post.');

        return response()->json([
            'status' => 'success'
        ]);
    } catch (\Exception $e) {
        \Log::error($e);

        return response()->json([
            'status' => 'error',
            'message' => 'Wystąpił błąd!'
        ])->setStatusCode(500);
    }
}

}