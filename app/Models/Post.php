<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'topic',
        'image_path',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likable');
    }

    public function likesCount(): int
    {
        return $this->likes()->count();
    }

    public function sheres(): HasMany
    {
        return $this->hasMany(Share::class);
    }

    public function sheresCount(): int
    {
        return $this->sheres()->count();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

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
}
