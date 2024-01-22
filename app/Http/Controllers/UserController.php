<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function usersPosts(User $user)
    {
        $followingIds = $user->following->pluck('id')->merge([$user->id]);

        $posts = Post::whereIn('user_id', $followingIds)
            ->orWhereHas('sheres', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('users.posts', compact('user', 'posts'));
    }

    public function showProfil(User $user)
    {
        $followingIds = $user->following->pluck('id')->merge([$user->id]);

        $posts = Post::whereIn('user_id', $followingIds)
            ->orWhereHas('sheres', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('users.showProfil', compact('user', 'posts'));
    }

    public function follow(User $user): RedirectResponse
    {
        Auth::user()->following()->toggle($user);

        return redirect()->route('users.showProfil', ['user' => $user])->with('success', 'Obserwacja została pomyślnie dodana.');
    }

    public function unfollow(User $user): RedirectResponse
    {
        Auth::user()->following()->toggle($user);

        return redirect()->route('users.showProfil', ['user' => $user])->with('success', 'Obserwacja została pomyślnie usunięta.');
    }
}
