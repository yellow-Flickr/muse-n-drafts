<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\TagRequest;
use App\Http\Resources\API\V1\TagResource;
use App\Models\Tag;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Gate;

class TagController extends Controller
{
    use ApiResponse;

    /**
     * List tags.
     *
     * Returns a paginated list of tags.
     *
     * @group Tags
     * @unauthenticated
     * @queryParam page integer The page number to return. Example: 2
     */
    public function index()
    {
        return TagResource::collection(Tag::paginate());
    }

    /**
     * Create a tag.
     *
     * @group Tags
     * @bodyParam data.attributes.name string required The tag name (maximum 10 characters). Example: Craft
     */
    public function store(TagRequest $request)
    {
        Gate::authorize('create', Tag::class);

        return new TagResource(Tag::create($request->input('data.attributes')));
    }

    /**
     * Get a tag.
     *
     * @group Tags
     * @urlParam tag integer required The tag ID. Example: 1
     */
    public function show($tagID)
    {

        $tag = Tag::findOrFail($tagID);

        return new TagResource($tag);

    }

    /**
     * Update a tag.
     *
     * @group Tags
     * @urlParam tag integer required The tag ID. Example: 1
     * @bodyParam data.attributes.name string required The tag name (maximum 10 characters). Example: Craft
     */
    public function update(TagRequest $request, $tagID)
    {
        $tag = Tag::findOrFail($tagID);
        Gate::authorize('update', $tag);
        $tag->update($request->input('data.attributes'));

        return new TagResource($tag);

    }

    /**
     * Replace a tag.
     *
     * @group Tags
     * @urlParam tag integer required The tag ID. Example: 1
     * @bodyParam data.attributes.name string required The tag name (maximum 10 characters). Example: Craft
     */
    public function replace(TagRequest $request, $tagID)
    {
        $tag = Tag::findOrFail($tagID);
        Gate::authorize('replace', $tag);
        $tag->update($request->input('data.attributes'));

        return new TagResource($tag);

    }

    /**
     * Delete a tag.
     *
     * @group Tags
     * @urlParam tag integer required The tag ID. Example: 1
     */
    public function destroy($tagID)
    {
        $tag = Tag::findOrFail($tagID);
        Gate::authorize('delete', $tag);
        $tag->delete($tag);

        return $this->ok('Tag deleted!');

    }
}
