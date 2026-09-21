<?php

namespace App\Http\Resources\API\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class PostResource extends JsonResource
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
            'type' => 'post',
            'id' => $this->id,
            'attributes' => [
                'title' => $this->title,
                // 'content' => Str::words($this->content, 7), 
                'content' => $this->when(
                    $request->routeIs('posts.index'),
                    Str::words($this->content, 7),
                    $this->content
                ), 
                'category' => $this->category->name,
                'tags' => $this->tags->pluck('name')->values(),
                'createdAt' => $this->created_at,
                'updatedAt' => $this->updated_at,
            ],
            // 'links' =>  [
            //     ['self' => route('posts.show',['post' => $this->id])]
            // ]

        ];
    }
}
