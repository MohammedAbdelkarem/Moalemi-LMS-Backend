<?php

namespace App\Http\Requests\MedicalProfile;

use App\Enums\DaysToTakeEnum;
use App\Enums\MedicineTimeEnum;
use App\Http\Requests\BaseApiRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateMedicinesRequest extends BaseApiRequest
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
            //medicines
            'medicines' => ['nullable' , 'array'],
            'medicines.*.text' => ['required' , 'string' , 'max:255'],
            'medicines.*.days_to_take' => ['nullable' , new Enum(DaysToTakeEnum::class)],
            //medicine_days
            'medicines.*.days' => ['nullable' , 'array'],
            'medicines.*.days.*.day_id' => ['required' , 'exists:days,id'],
            //medicine_times
            'medicines.*.days.*.time' => ['nullable' , 'array'],
            'medicines.*.days.*.time.*' => ['required' , 'date_format:H:i'],
            'medicines.*.days.*.other_time' => ['nullable' , 'array'],
            'medicines.*.days.*.other_time.*' => ['required' , new Enum(MedicineTimeEnum::class)],
        ];
    }
}
