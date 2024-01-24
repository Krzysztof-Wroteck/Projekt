<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('posts')->group(function () {

        Route::post('store', [PostController::class, 'store'])->name('posts.store');
        Route::get('/create', [PostController::class, 'create'])->name('posts.create');
        Route::get('/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
        Route::put('/{post}', [PostController::class, 'update'])->name('posts.update');
        Route::get('', [PostController::class, 'index'])->name('posts.index');

        Route::get('/{post}/comments', [CommentController::class, 'index'])->name('comments.index');
        Route::post('/{post}/comments', [CommentController::class, 'store'])->name('posts.comments.store');
        Route::get('/comments/{comment}/edit', [CommentController::class, 'edit'])->name('comments.edit');
        Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');

    });
    Route::prefix('users')->group(function () {
        Route::get('/{user}/posts', [UserController::class, 'posts'])->name('users.posts');
        Route::get('/{user}/show', [UserController::class, 'show'])->name('users.show');
        Route::post('/{user}/follow', [UserController::class, 'follow'])->name('users.follow');
        Route::post('/{user}/unfollow', [UserController::class, 'unfollow'])->name('users.unfollow');
    });

});

require __DIR__.'/auth.php';
