<?php

namespace Database\Factories;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition(): array
    {
        return [
            'topic' => $this->faker->sentence,
            'image_path' => null,
            'user_id' => \App\Models\User::factory(),
            'post_id' => \App\Models\Post::factory(),
        ];
    }
}
