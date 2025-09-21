<?php

namespace App\Http\Requests\Transaction;

use Illuminate\Validation\Rule;
use App\Http\Requests\BaseApiRequest;

class CreateCuponRequest extends BaseApiRequest
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
            'amount' => ['required', 'integer', 'min:1' , 'max:5000000'],
        ];
    }
}
