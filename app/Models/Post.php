<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
class Post extends Model
{

    use HasFactory;
    protected $fillable = [
        'Temat',
        'image_path',
    ];

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

}