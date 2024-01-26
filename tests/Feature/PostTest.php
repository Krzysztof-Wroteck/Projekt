<?php


namespace Tests\Feature;

use App\Models\User;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker; 
use Illuminate\Database\Eloquent\Factory; 
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
