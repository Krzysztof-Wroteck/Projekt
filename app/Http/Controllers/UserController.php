<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function usersPosts()
    {
        $user = Auth::user();
        $posts = Post::where('user_id', $user->id)
        ->orWhereHas('sheres', function ($query) use ($user) {
           $query->where('user_id', $user->id);
        })
        ->get();

        return view('users.posts', compact('posts'));
    }


    


    public function showPosts(User $user)
    {
        $posts = $user->posts; 

        return view('users.posts', compact('user', 'posts'));
    }
}
