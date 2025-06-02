<?php

namespace App\Http\Requests\MedicalProfile;

use App\Enums\SmokeEnum;
use App\Enums\BloodTypeEnum;
use App\Enums\DaysToTakeEnum;
use App\Enums\MedicineTimeEnum;
use App\Enums\TreatmentStatusEnum;
use App\Http\Requests\BaseApiRequest;
use Illuminate\Validation\Rules\Enum;

class CreateMedicalProfileRequest extends BaseApiRequest
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
            //patient
            'full_name' => ['nullable' , 'string' , 'max:255'],
            'birth_date' => ['nullable' , 'date'],
            'is_male' => ['nullable' , 'boolean'],
            "avatar" => [
                "nullable",
                "file",
                "image",
                "max:1024",
                "dimensions:min_width=100,min_height=100,max_width=2048,max_height=2048",
                "mimes:png,jpg,jpeg,webpm"
            ],
            "relation" => ['nullable' , 'string'],
            'smoking' => ['nullable' , new Enum(SmokeEnum::class)],
            'alcohol' => ['nullable' , 'boolean'],
            'height' => ['nullable' , 'integer' , 'between:25,250'],
            'weight' => ['nullable' , 'integer' , 'between:1,400'],
            'blood_type' => ['nullable' , new Enum(BloodTypeEnum::class)],
            'chronic_diseases' => ['nullable' , 'string'],
            'notes' => ['nullable' , 'string'],
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
            //instruction
            'instructions' => ['nullable' , 'array'],
            'instructions.*.text' => ['required' , 'string' , 'max:255'],
        ];
    }
}
