<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->string('topic');
            $table->string('image_path', 150)->nullable();
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('no action');
            $table->foreignId('post_id')
                ->constrained('posts')
                ->onDelete('no action');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
