<?php

namespace App\Http\Queries\API\V1;

use Illuminate\Database\Eloquent\Builder;

class PostSearchQuery
{
    public function apply(
        Builder $query,
        ?string $search
    ): Builder {
        if (! $search) {
            return $query;
        }

        return $query->where(function (Builder $query) use ($search) {
            $query
                ->where('name', 'like', "%{$search}%")
                ->orWhere('content', 'like', "%{$search}%");
        });

    }
}
