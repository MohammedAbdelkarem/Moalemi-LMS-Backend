<?php

namespace App\Http\Requests\MedicalProfile;

use App\Enums\SmokeEnum;
use App\Enums\BloodTypeEnum;
use App\Http\Requests\BaseApiRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateMedicalProfileRequest extends BaseApiRequest
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
        ];
    }
}
