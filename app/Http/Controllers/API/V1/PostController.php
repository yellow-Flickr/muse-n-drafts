<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Queries\API\V1\PostFilterQuery;
use App\Http\Queries\API\V1\PostSearchQuery;
use App\Http\Requests\API\V1\StorePostRequest;
use App\Http\Requests\API\V1\UpdatePostRequest;
use App\Http\Resources\V1\PostResource;
use App\Models\Post;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class PostController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, PostSearchQuery $postSearch, PostFilterQuery $postFilter)
    {
        $query = Post::query();

        // if ($request->filled('category')) {
        //     $query->category($request->string('category')->toString());
        // }

        // if ($request->filled('tag')) {
        //     $query->tag($request->string('tag')->toString());
        // }

        $postSearch->apply(
            $query,
            $request->string('search')->trim()->value()
        );

        $postFilter->apply(
            $query,
            $request->only([
                'category',
                'tag',
            ])
        );

        return PostResource::collection($query->sort($request->string('sort')->toString())->paginate());
    }

    // /**
    //  * Show the form for creating a new resource.
    //  */
    // public function create()
    // {
    //     //
    // }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        // dd($request->collect()['tags']->values());

        $post = Post::create($request->collect()->toArray());
        $post->tags()->sync($request->collect()['tags']);

        return response()->json(new PostResource($post), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return new PostResource($post);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
    }
}
