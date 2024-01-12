<?php

namespace App\Policies;

use App\Models\User;
use App\Enums\UserRole;
use App\Models\Post;

class PostPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function deletePost(User $user, $postId)
{
    $post = Post::findOrFail($postId);

    return $user->isAdmin() || $user;
}


public function edit(User $user, Post $post)
{
    return $user->isAdmin() || $user->id === $post->user_id;
}
}
