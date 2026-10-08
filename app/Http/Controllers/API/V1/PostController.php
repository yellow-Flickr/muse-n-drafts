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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{
    use ApiResponse;

    /**
     * List posts.
     *
     * Returns a paginated list. Without a token, all posts are listed; with a
     * valid token, only the authenticated user's posts are listed.
     *
     * @group Posts
     * @unauthenticated
     * @queryParam search string Search post titles and content. Example: writing
     * @queryParam category string Filter by the exact category name. Example: Essays
     * @queryParam tag string Filter by the exact tag name. Example: Craft
     * @queryParam sort string Sort by a post column; prefix with "-" for descending order. Defaults to id ascending. Example: -created_at
     * @queryParam page integer The page number to return. Example: 2
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
     * Create a post.
     *
     * @group Posts
     * @bodyParam data.attributes.title string required The post title (maximum 100 characters). Example: Notes on revision
     * @bodyParam data.attributes.content string required The post content. Example: A short reflection on revising a first draft.
     * @bodyParam data.attributes.category string required The name of an existing category. Example: Essays
     * @bodyParam data.attributes.tags string[] Optional names of existing tags (maximum 4). Example: ["Craft", "Writing"]
     */
    public function store(StorePostRequest $request)
    {
        // policy
        Gate::authorize('store', Post::class);
        // dd($request->input('data.attributes'));
        // return new PostResource(Post::create($request->mappedAttributes()));
        $post = Post::create($request->input('data.attributes'));
        $post->tags()->sync($request->input('data.attributes.tags'));

        // return response()->json(new PostResource($post), 201);}

        return new PostResource($post);

    }

    /**
     * Get a post.
     *
     * @group Posts
     * @unauthenticated
     * @urlParam post integer required The post ID. Example: 1
     */
    public function show($post_id)
    {
        $post = Post::findorFail($post_id);

        return new PostResource($post);

    }

    /**
     * Update a post.
     *
     * @group Posts
     * @urlParam post integer required The post ID. Example: 1
     * @bodyParam data.attributes.title string The post title (maximum 100 characters). Example: Notes on revision
     * @bodyParam data.attributes.content string The post content. Example: A short reflection on revising a first draft.
     * @bodyParam data.attributes.category string The name of an existing category. Example: Essays
     * @bodyParam data.attributes.tags string[] Names of existing tags (maximum 4). Example: ["Craft", "Writing"]
     */
    public function update(UpdatePostRequest $request, $post_id)
    {
        $post = Post::findorFail($post_id);
        Gate::authorize('update', $post);
        $post->update($request->input('data.attributes'));
        if ($request->exists('data.attributes.tags')) {
            $post->tags()->sync($request->input('data.attributes.tags'));
        }

        return new PostResource($post);

    }

    /**
     * Replace a post.
     *
     * @group Posts
     * @urlParam post integer required The post ID. Example: 1
     * @bodyParam data.attributes.title string required The post title (maximum 100 characters). Example: Notes on revision
     * @bodyParam data.attributes.content string required The post content. Example: A short reflection on revising a first draft.
     * @bodyParam data.attributes.category string required The name of an existing category. Example: Essays
     * @bodyParam data.attributes.tags string[] Optional names of existing tags (maximum 4). Example: ["Craft", "Writing"]
     */
    public function replace(ReplacePostRequest $request, $post_id)
    {
            $post = Post::findorFail($post_id);
            Gate::authorize('replace', $post);
            // dd($request->toArray());
            $post->update($request->input('data.attributes'));
            $post->tags()->sync($request->input('data.attributes.tags'));

            return new PostResource($post);
 
    }

    /**
     * Delete a post.
     *
     * @group Posts
     * @urlParam post integer required The post ID. Example: 1
     */
    public function destroy($post_id)
    {
            $post = Post::findorFail($post_id);
            Gate::authorize('delete', $post);
            $post->delete();

            return $this->ok('Post Deleted!');
 
    }
}
