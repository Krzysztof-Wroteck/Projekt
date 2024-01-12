<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::middleware(['auth:sanctum', 'admin_or_author'])->group(function () {
    Route::delete('/posts/list/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::delete('/posts/{post}/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroyApi');
     
    Route::post('/posts/shere/{post}', [PostController::class, 'sherePost'])->name('posts.shere');

});


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/api/posts/list/{post}', [PostController::class, 'likePost'])->name('posts.like.api');

  

    
});