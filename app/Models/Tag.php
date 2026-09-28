<?php

namespace App\Models;

use Database\Factories\TagFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    /** @use HasFactory<TagFactory> */
    use HasFactory;

    public function posts()
    {
        return $this->belongsToMany(Post::class, 'post_tag');

    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'createdBy');
    }
}
