<?php

namespace Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Post;
use Illuminate\Support\Str;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition()
    {
        return [
            'temat' => $this->faker->sentence(),
            'user_id' => \App\Models\User::factory(),
            
        ];
    }
}