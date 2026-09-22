<?php

namespace App\Http\Requests\API\V1;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Override;

class UserRequest extends FormRequest
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
            'name' => 'required|string',
            'email' => 'required|string|email|unique:App\Models\User,email',
            'password' => 'required|confirmed|min:8',
        ];
    }

    #[Override]
    public function messages()
    {
        return [
            'email:unique'=>'User already exists!',
            'email:email'=>'Valid email required!',
            'password:min'=>'Password requires 8 characters minimum!'
        ];
    }
}
