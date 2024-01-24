<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    /**
     * Create a new policy instance.
     */
    public function deletePost(User $user, Post $post)
    {

        return $user->isAdmin() || $user->id === $post->user_id;
    }

    public function edit(User $user, Post $post)
    {
        return $user->isAdmin() || $user->id === $post->user_id;
    }
}
