<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\CategoryRequest;
use App\Http\Resources\API\V1\CategoryResource;
use App\Models\Category;
use App\Traits\ApiResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Gate;

class CategoryController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return CategoryResource::collection(Category::paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        try {
            Gate::authorize('create', Category::class);

            return new CategoryResource(Category::create($request->input('data.attributes')));
        } catch (AuthorizationException $th) {
            return $this->error('You are not authorised for this action!', 403);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($categoryID)
    {
        try {
            $category = Category::findOrFail($categoryID);

            return new CategoryResource($category);

        } catch (ModelNotFoundException $th) {
            return $this->error('Category not found!', 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, $categoryID)
    {
        try {
            $category = Category::findOrFail($categoryID);
            Gate::authorize('update', $category);
            $category->update($request->input('data.attributes'));

            return new CategoryResource($category);
        } catch (ModelNotFoundException $th) {
            return $this->error('Category not found!', 404);
        } catch (AuthorizationException $th) {
            return $this->error('You are not authorised for this action!', 403);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function replace(CategoryRequest $request, $categoryID)
    {
        try {
            $category = Category::findOrFail($categoryID);
            Gate::authorize('update', $category);
            $category->update($request->input('data.attributes'));

            return new CategoryResource($category);
        } catch (ModelNotFoundException $th) {
            return $this->error('Category not found!', 404);
        } catch (AuthorizationException $th) {
            return $this->error('You are not authorised for this action!', 403);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($categoryID)
    {
        try {
            $category = Category::findOrFail($categoryID);
            Gate::authorize('delete', $category);
            $category->delete();

            return $this->ok('Category Deleted!');
        } catch (ModelNotFoundException $th) {
            return $this->error('Category not found!', 404);
        } catch (AuthorizationException $th) {
            return $this->error('You are not authorised for this action!', 403);
        }
    }
}
