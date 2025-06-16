<?php

namespace App\Http\Requests\Reservation;

use App\Enums\BloodTypeEnum;
use App\Http\Requests\BaseApiRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateReportRequest extends BaseApiRequest
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
            //report info
            'title' => ['nullable' , 'string'],
            'description' => ['nullable' , 'string'],
            'notes' => ['nullable' , 'string'],

            //patient updated info
            'patient_info_updated'      => ['required' , 'boolean'],
            'new_height'                => ['nullable' , 'integer' , 'between:25,250'],
            'new_weight'                => ['nullable' , 'integer' , 'between:1,400'],
            'new_blood_type'            => ['nullable' , new Enum(BloodTypeEnum::class)],
            'new_chronic_diseases'      => ['nullable' , 'string'],
            'new_notes'                 => ['nullable' , 'string'],
        ];
    }
}
