<?php

namespace App\Models;

use App\Policies\PostPolicy;
use Database\Factories\PostFactory;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// #[UsePolicy(PostPolicy::class)]
class Post extends Model
{
    /** @use HasFactory<PostFactory> */
    use HasFactory;


    protected $fillable = [
        'title',
        'content',
        'category_id',
        'author_id',
        // 'tags',
    ];

    protected $with = [
        'category',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);

    }
    public function author()
    {
        return $this->belongsTo(User::class,'author_id');

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
        $sort = $sort ?: 'id';

        if (str_starts_with($sort, '-')) {
            $direction = 'desc';
            $sort = substr($sort, 1);
        }

        return $query->orderBy($sort, $direction);

    }
}
