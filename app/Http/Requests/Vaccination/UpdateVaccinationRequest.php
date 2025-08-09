<?php

namespace App\Http\Requests\Vaccination;

use App\Http\Requests\BaseApiRequest;

class UpdateVaccinationRequest extends BaseApiRequest
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
            'checked' => ['nullable' , 'boolean'],
            'note' => ['nullable' , 'string' , 'max:1000']
        ];
    }
}
