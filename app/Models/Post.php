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


    protected $fillable = [
        'title',
        'content',
        'category_id',
        // 'tags',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);

    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'post_tag');

    }

    // #[Scope]
    // public function scopeCategory(
    //     Builder $query,
    //     string $category
    // ) {
    //  return   $query->whereHas('category', function ($query) use ($category) {
    //         $query->where('name', $category);
    //     });
    // }

    // #[Scope]
    // public function scopeTag(
    //     Builder $query,
    //     string $tag
    // ) {
    //  return   $query->whereHas('tags', function ($query) use ($tag) {
    //         $query->where('name', $tag);
    //     });
    // }

    #[Scope]
    public function scopeSort(
        Builder $query,
       ?string $sort = 'id'
    ) {
        $direction = 'asc';

        if (strpos($sort, '-') == 0) {
            $direction = 'desc';
            $sort = substr($sort, 1);
        }

        return $query->orderBy($sort, $direction);

    }
}
