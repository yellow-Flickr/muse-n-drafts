<?php

namespace App\Http\Requests\API\V1;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Override;

class CategoryRequest extends FormRequest
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
            'data.attributes.name' => 'required|string|max:10|unique:App\Models\Category,name',
        ];
    }

    #[Override]
    public function messages(): array
    {
        return [
            'data.attributes.name.max' => 'Category name is too long!',
            'data.attributes.name.required' => 'A category name is required!',
            'data.attributes.name.unique' => 'Category already exist!',
        ];
    }

    #[Override]
    protected function passedValidation()
    {
        if ($this->isMethod('post') || $this->isMethod('put')) {
            $this->merge([
                'data.attributes.createdBy' => $this->user()->id,
            ]
            );
        }

    }
}
