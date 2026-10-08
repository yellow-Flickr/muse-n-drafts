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
         * List categories.
         *
         * Returns a paginated list of categories.
         *
         * @group Categories
         * @unauthenticated
         * @queryParam page integer The page number to return. Example: 2
         */
    public function index()
    {
        return CategoryResource::collection(Category::paginate());
    }

        /**
         * Create a category.
         *
         * @group Categories
         * @bodyParam data.attributes.name string required The category name (maximum 10 characters). Example: Essays
         */
    public function store(CategoryRequest $request)
    {
            Gate::authorize('create', Category::class);

            return new CategoryResource(Category::create($request->input('data.attributes')));
  
    }

        /**
         * Get a category.
         *
         * @group Categories
         * @urlParam category integer required The category ID. Example: 1
         */
    public function show($categoryID)
    {
            $category = Category::findOrFail($categoryID);

            return new CategoryResource($category);

  
    }

        /**
         * Update a category.
         *
         * @group Categories
         * @urlParam category integer required The category ID. Example: 1
         * @bodyParam data.attributes.name string required The category name (maximum 10 characters). Example: Essays
         */
    public function update(CategoryRequest $request, $categoryID)
    {
            $category = Category::findOrFail($categoryID);
            Gate::authorize('update', $category);
            $category->update($request->input('data.attributes'));

            return new CategoryResource($category);
 
    }

        /**
         * Replace a category.
         *
         * @group Categories
         * @urlParam category integer required The category ID. Example: 1
         * @bodyParam data.attributes.name string required The category name (maximum 10 characters). Example: Essays
         */
    public function replace(CategoryRequest $request, $categoryID)
    {
            $category = Category::findOrFail($categoryID);
            Gate::authorize('update', $category);
            $category->update($request->input('data.attributes'));

            return new CategoryResource($category);
 
    }

        /**
         * Delete a category.
         *
         * @group Categories
         * @urlParam category integer required The category ID. Example: 1
         */
    public function destroy($categoryID)
    {
            $category = Category::findOrFail($categoryID);
            Gate::authorize('delete', $category);
            $category->delete();

            return $this->ok('Category Deleted!');

    }
}
