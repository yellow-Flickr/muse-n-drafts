<?php

namespace App\Models;

use Database\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /** @use HasFactory<CategoryFactory> */
    use HasFactory;

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'createdBy');

    }
}
