<?php

namespace App\Http\Requests\Category;

use Illuminate\Validation\Rule;
use App\Http\Requests\BaseApiRequest;

class UpdateCategoryRequest extends BaseApiRequest
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
        $id = $this->route('category');

        return [
            'name' => ['required', 'string','max:255', Rule::unique('categories', 'name')->ignore($id)],
            'bio'  => ['nullable','string','max:255'],
        ];
    }
}
