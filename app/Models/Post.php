<?php

namespace App\Models;

use Database\Factories\PostFactory;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    /** @use HasFactory<PostFactory> */
    use HasFactory;

    public function category()
    {
        return $this->belongsTo(Category::class);

    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'post_tag');

    }

    #[Scope]
    protected function withCategory(
        Builder $query,
        string $category
    ): void {
        $query->whereHas('category', function ($query) use ($category) {
            $query->where('name', $category);
        });
    }

    #[Scope]
    protected function haveTag(
        Builder $query,
        string $tag
    ): void {
        $query->whereHas('tags', function ($query) use ($tag) {
            $query->where('name', $tag);
        });
    }
}
