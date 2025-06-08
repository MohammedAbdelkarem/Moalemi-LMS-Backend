<?php

namespace App\Http\Requests\MedicalProfile;

use App\Http\Requests\BaseApiRequest;

class UpdateInstructionsRequest extends BaseApiRequest
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
            'instructions' => ['nullable' , 'array'],
            'instructions.*.text' => ['required' , 'string' , 'max:255'],
        ];
    }
}
