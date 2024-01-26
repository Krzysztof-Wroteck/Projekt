<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_Comment_show(): void
    {

        $user = User::factory()->create();

        $this->actingAs($user);


        $comment = Comment::factory()->create();

        $response = $this->get("/posts/{$comment->post_id}/comments");

        $response->assertSee($comment->topic);

        $response->assertStatus(200);
    }
}
