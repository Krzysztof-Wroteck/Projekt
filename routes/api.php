<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

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

});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/posts/list/{post}', [PostController::class, 'likePost'])->name('posts.like.api');
    Route::post('/posts/shere/{post}', [PostController::class, 'sherePost'])->name('posts.shere');
    Route::post('/posts/{post}/comments/{comment}/like', [CommentController::class, 'like'])->name('comments.like.api');
});
