<?php

namespace App\Http\Requests\Reservation;

use App\Http\Requests\BaseApiRequest;

class AppointmentRequest extends BaseApiRequest
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
            'patient_id' => ['required', 'exists:patients,id'],
            'doctor_id' => ['required', 'exists:doctors,id'],
            'shift_id' => ['required', 'exists:shifts,id'],
            'date' => ['required', 'date'],
            'text' => ['required'],
            "images" => ['nullable' , 'array'],
            "images.*.image" => [
                'required',
               'mimes:jpeg,jpg,png,webp',
               'max:4096'
            ],
            "images.*.title" => ['required','max:255'],
            'visits_available' => ['required', 'boolean'],
            'notes' => ['nullable']
        ];
    }
}
