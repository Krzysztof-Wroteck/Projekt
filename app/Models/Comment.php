<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;


class Comment extends Model
{
    use HasFactory;


    protected $fillable = ['temat', 'image_path', 'user_id', 'post_id'];

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
