<?php

namespace App\Http\Requests\Reservation;

use App\Http\Requests\BaseApiRequest;

class RateVisitRequest extends BaseApiRequest
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
            'rate' => ['required' , 'integer' , 'min:1' , 'max:5'],
            'comment' => ['nullable' , 'string' , 'max:255'],
            "images"               => ['nullable' , 'array'],
            "images.*.image"       => [
                'required',
               'mimes:jpeg,jpg,png,webp,pdf',
               'max:4096'
            ],
        ];
    }
}
