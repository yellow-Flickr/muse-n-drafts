<?php

namespace App\Http\Queries\API\V1;

use Illuminate\Database\Eloquent\Builder;

class PostFilterQuery
{
    public function apply(
        Builder $query,
        array $filters
    ): Builder {

        return $query
            ->when(
                isset($filters['category']),
                fn (Builder $query) => 
                    $query->whereHas('category', function ($query) use ($filters) {
                        $query->where('name', $filters['category']);
                })
            )
            ->when(
                isset($filters['tag']),
                fn (Builder $query) => 
                    $query->whereHas('tags', function ($query) use ($filters) {
                        $query->where('name', $filters['tag']);
                })
            );

    }
}
