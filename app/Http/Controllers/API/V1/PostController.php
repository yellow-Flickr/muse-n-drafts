<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Queries\API\V1\PostFilterQuery;
use App\Http\Queries\API\V1\PostSearchQuery;
use App\Http\Requests\API\V1\ReplacePostRequest;
use App\Http\Requests\API\V1\StorePostRequest;
use App\Http\Requests\API\V1\UpdatePostRequest;
use App\Http\Resources\API\V1\PostResource;
use App\Models\Post;
use App\Traits\ApiResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, PostSearchQuery $postSearch, PostFilterQuery $postFilter)
    {
        // $query = auth()->user()->posts()->with(['category','tags'])->getQuery();

        $user = Auth::guard('sanctum')->user();
        if ($user) {
            $query = Post::with(['category', 'tags'])
                ->where('author_id', $user->id);
        } else {
            $query = Post::with(['category', 'tags']);
        }

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
        try {
            // policy
            Gate::authorize('store', Post::class);
            // dd($request->input('data.attributes'));
            // return new PostResource(Post::create($request->mappedAttributes()));
            $post = Post::create($request->input('data.attributes'));
            $post->tags()->sync($request->input('data.attributes.tags'));

            // return response()->json(new PostResource($post), 201);}

            return new PostResource($post);
        } catch (AuthorizationException $th) {
            return $this->error('You are not authorised for this action!', 403);
            // throw $th;
        }

    }

    /**
     * Display the specified resource.
     */
    public function show($post_id)
    {
        try {
            $post = Post::findorFail($post_id);

            return new PostResource($post);
        } catch (ModelNotFoundException $th) {
            return $this->error('Post not found!', 404);
        }
    }

    /**
     * Update the specified resource in storage. PATCH
     */
    public function update(UpdatePostRequest $request, $post_id)
    {
        try {
            $post = Post::findorFail($post_id);
            Gate::authorize('update', $post);
            $post->update($request->input('data.attributes'));
            if ($request->exists('data.attributes.tags')) {
                $post->tags()->sync($request->input('data.attributes.tags'));
            }

            return new PostResource($post);
        } catch (ModelNotFoundException $th) {
            return $this->error('Post not found!', 404);
        } catch (AuthorizationException $th) {
            return $this->error('You are not authorised for this action!', 403);
        }
    }

    /**
     * Replace the specified resource in storage. PUT
     */
    public function replace(ReplacePostRequest $request, $post_id)
    {
        try {
            $post = Post::findorFail($post_id);
            Gate::authorize('replace', $post);
            // dd($request->toArray());
            $post->update($request->input('data.attributes'));
            $post->tags()->sync($request->input('data.attributes.tags'));

            return new PostResource($post);
        } catch (ModelNotFoundException $th) {
            return $this->error('Post not found!', 404);
        } catch (AuthorizationException $th) {
            return $this->error('You are not authorised for this action!', 403);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($post_id)
    {
        try {
            $post = Post::findorFail($post_id);
            Gate::authorize('delete', $post);
            $post->delete();

            return $this->ok('Post Deleted!');
        } catch (ModelNotFoundException $th) {
            return $this->error('Post not found!', 404);
        } catch (AuthorizationException $th) {
            return $this->error('You are not authorised for this action!', 403);
        }
    }
}
