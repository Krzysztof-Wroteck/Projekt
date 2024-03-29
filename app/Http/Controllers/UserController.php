<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function posts(User $user)
    {
        $posts = Post::whereIn('user_id', $user->following->pluck('id'))
            ->orWhere('user_id', $user->id)
            ->orWhereHas('sheres', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('users.posts', compact('user', 'posts'));
    }

    public function show(User $user)
    {
        $posts = Post::whereIn('user_id', $user->following->pluck('id'))
            ->orWhere('user_id', $user->id)
            ->orWhereHas('sheres', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('users.show', compact('user', 'posts'));
    }

    public function follow(User $user): RedirectResponse
    {
        Auth::user()->following()->toggle($user);

        return redirect()->route('users.show', ['user' => $user])->with('success', 'Obserwacja została pomyślnie dodana.');
    }

    public function unfollow(User $user): RedirectResponse
    {
        Auth::user()->following()->toggle($user);

        return redirect()->route('users.show', ['user' => $user])->with('success', 'Obserwacja została pomyślnie usunięta.');
    }
}
