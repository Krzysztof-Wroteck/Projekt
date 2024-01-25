<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Storage;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['topic', 'image_path', 'user_id', 'post_id'];

    public function imageUrl(): string
    {
        return $this->imageExists()
            ? Storage::url($this->image_path)
            : Storage::url(config('filesystems.default_image'));
    }

    public function imageExists(): bool
    {
        return $this->image_path !== null
            && Storage::disk('public')->exists($this->image_path);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likable');
    }

    public function likesCount(): int
    {
        return $this->likes()->count();
    }
}
