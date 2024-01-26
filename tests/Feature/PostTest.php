<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    public function test_Post_show()
    {
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('secret'),
        ]);
        $this->actingAs($user);

        Post::create([
            'topic' => 'aaaa',
            'user_id' => $user->id,
        ]);

        $response = $this->get('/posts');

        $response->assertSee('aaaa');
        $response->assertStatus(200);
    }
}
