<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\TagRequest;
use App\Http\Resources\API\V1\TagResource;
use App\Models\Tag;
use App\Traits\ApiResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Gate;

class TagController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return TagResource::collection(Tag::paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TagRequest $request)
    {
        try {
            Gate::authorize('create', Tag::class);

            return new TagResource(Tag::create($request->input('data.attributes')));
        } catch (AuthorizationException $th) {
            $this->error('You are not authorised for this action!', 403);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($tagID)
    {
        try {

            $tag = Tag::findOrFail($tagID);

            return new TagResource($tag);
        } catch (ModelNotFoundException $th) {
            $this->error('Tag not found!', 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TagRequest $request, $tagID)
    {
        try {
            $tag = Tag::findOrFail($tagID);
            Gate::authorize('update', $tag);
            $tag->update($request->input('data.attributes'));

            return new TagResource($tag);
        } catch (ModelNotFoundException $th) {
            $this->error('Tag not found!', 404);
        } catch (AuthorizationException $th) {
            $this->error('You are not authorised for this action!', 403);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function replace(TagRequest $request, $tagID)
    {
        try {
            $tag = Tag::findOrFail($tagID);
            Gate::authorize('replace', $tag);
            $tag->update($request->input('data.attributes'));

            return new TagResource($tag);
        } catch (ModelNotFoundException $th) {
            $this->error('Tag not found!', 404);
        } catch (AuthorizationException $th) {
            $this->error('You are not authorised for this action!', 403);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($tagID)
    {
        try {
            $tag = Tag::findOrFail($tagID);
            Gate::authorize('delete', $tag);
            $tag->delete($tag);

            return $this->ok('Tag deleted!');
        } catch (ModelNotFoundException $th) {
            $this->error('Tag not found!', 404);
        } catch (AuthorizationException $th) {
            $this->error('You are not authorised for this action!', 403);
        }
    }
}
