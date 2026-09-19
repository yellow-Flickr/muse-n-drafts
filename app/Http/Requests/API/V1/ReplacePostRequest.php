<?php

namespace App\Http\Requests\API\V1;

use App\Models\Category;
use App\Models\Tag;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Override;

class ReplacePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // "data" => 'required|array',
            // "data.attributes" => 'required|array',
            'data.attributes.title' => 'required|string|max:100',
            'data.attributes.content' => 'required|string',
            'data.attributes.category' => 'required|string|exists:App\Models\Category,name',
            'data.attributes.tags' => 'sometimes|nullable|array|max:4',
            'data.attributes.tags.*' => 'string|distinct|exists:App\Models\Tag,name',
        ];
    }

    // #[Override]
    public function messages(): array
    {
        return [
            'data.attributes.title.max' => 'Title too long!',
            'data.attributes.category.exists' => 'Only existing category names are allowed!',
            'data.attributes.tags.array' => 'Tags must be in an array!',
            'data.attributes.tags.max' => 'No more than 4 tags are allowed!',
            'data.attributes.tags.*.exists' => 'Only existing tag names are allowed!',
            'data.attributes.tags.*.distinct' => 'Duplicate tags are not allowed!',
        ];
    }

    #[Override]
    protected function passedValidation()
    {
        $categoryID = Category::where('name', $this->input('data.attributes.category'))->value('id');
        $tagIDs = Tag::whereIn('name', $this->input('data.attributes.tags', []))->pluck('id')->values();
        $this->replace([
            'title' =>$this->input('data.attributes.title'),
            'content' =>$this->input('data.attributes.content'),
            'category_id' => $categoryID,
            'tags' => $tagIDs,
        ]);
    }
}
