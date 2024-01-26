<?php

namespace Tests\Feature;

use App\Models\Comment;

use App\Models\User;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker; 
use Illuminate\Database\Eloquent\Factory; 
use Tests\TestCase;

class CommentTest extends TestCase
{

    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_Comment_show(): void
    {

        $user = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('secret'), 
        ]);       
         $this->actingAs($user);

        $post = Post::create([
            'topic' => 'aaaa', 
            'user_id' => $user->id,
        ]);

Comment::create([
'topic'=>'bbbb', 
'user_id' => $user->id,
'post_id'=> $post->id
]
);

$response = $this->get("/posts/{$post->id}/comments");

        $response->assertSee('bbbb'); 

        $response->assertStatus(200);
    }


    
}
