<?php

namespace App\Http\Requests\Reservation;

use App\Http\Requests\BaseApiRequest;

class AcceptRequest extends BaseApiRequest
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
            'time_to_come' => ['required' , 'date_format:H:i']
        ];
    }
}
