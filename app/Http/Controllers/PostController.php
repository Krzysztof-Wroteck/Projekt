<?php

namespace App\Http\Controllers;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(): View
    {
        return view("posts.index", [
            'posts' => Post::paginate(10)
        ]);
    }

    public function create(): View
    {
        return view("products.create");
    }

    

}
