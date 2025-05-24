<?php

namespace App\Http\Requests\Article;

use Illuminate\Validation\Rule;
use App\Http\Requests\BaseApiRequest;

class CreateArticleRequest extends BaseApiRequest
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
            'title' => ['required', 'string','max:255'],
            'body'  => ['required','string'],
            'image'                      => [
                'required',
                'mimes:jpeg,jpg,png,webp',
                'max:4096'
            ],
        ];
    }
}
