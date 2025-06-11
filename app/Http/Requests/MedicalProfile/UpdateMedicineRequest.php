<?php

namespace App\Http\Requests\MedicalProfile;

use App\Enums\DaysToTakeEnum;
use App\Enums\MedicineTimeEnum;
use App\Enums\TreatmentStatusEnum;
use App\Http\Requests\BaseApiRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateMedicineRequest extends BaseApiRequest
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
            //medicine
            'text'          => ['required' , 'string' , 'max:255'],
            'status'        => ['required' , new Enum(TreatmentStatusEnum::class)],
            'end_date'      => ['nullable' , 'date'],
            'other_end_date'=> ['nullable' , 'string'],
            'days_to_take'  => ['nullable' , new Enum(DaysToTakeEnum::class)],
            //medicine_days
            'days'          => ['nullable' , 'array'],
            'days.*.day_id' => ['required' , 'exists:days,id'],
            //medicine_times
            'days.*.time'           => ['nullable' , 'array'],
            'days.*.time.*'         => ['required' , 'date_format:H:i'],
            'days.*.other_time'     => ['nullable' , 'array'],
            'days.*.other_time.*'   => ['required' , new Enum(MedicineTimeEnum::class)],
        ];
    }
}
