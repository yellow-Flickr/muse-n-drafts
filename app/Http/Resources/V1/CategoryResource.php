<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    // public static $wrap = 'posts';
    public function toArray(Request $request): array
    {
        return [
            'type' => 'category',
            'id' => $this->id,
            'attributes' => [
                'name' => $this->name,
                'noOfPosts' => $this->posts->pluck('id')->values()->count(),
                'createdAt' => $this->created_at,
                'updatedAt' => $this->updated_at,
            ],
            // 'links' =>  [
            //     ['self' => route('posts.show',['post' => $this->id])]
            // ]

        ];
    }
}
