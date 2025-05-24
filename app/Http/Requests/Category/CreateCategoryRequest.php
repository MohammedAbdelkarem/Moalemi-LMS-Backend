<?php

namespace App\Http\Requests\Category;

use Illuminate\Validation\Rule;
use App\Http\Requests\BaseApiRequest;

class CreateCategoryRequest extends BaseApiRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        return [
            'name' => ['required', 'string','max:255', Rule::unique('categories', 'name')],
            'bio'  => ['nullable','string','max:255'],
            'image'                      => [
                'required',
                'mimes:jpeg,jpg,png,webp',
                'max:4096'
            ],
        ];
    }
}
