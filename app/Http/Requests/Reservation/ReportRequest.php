<?php

namespace App\Http\Requests\Reservation;

use App\Enums\BloodTypeEnum;
use App\Enums\DaysToTakeEnum;
use App\Enums\MedicineTimeEnum;
use App\Enums\TreatmentStatusEnum;
use App\Http\Requests\BaseApiRequest;
use Illuminate\Validation\Rules\Enum;

class ReportRequest extends BaseApiRequest
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
            'title'                     => ['required' , 'string'],
            'description'               => ['required' , 'string'],
            "attachments"               => ['nullable' , 'array'],
            "attachments.*.image"       => [
                'required',
               'mimes:jpeg,jpg,png,webp,pdf',
               'max:4096'
            ],
            "attachments.*.title"       => ['required','max:255'],
            'notes'                     => ['nullable' , 'string'],
            //patient updated info
            'patient_info_updated'      => ['required' , 'boolean'],
            'new_height'                => ['nullable' , 'integer' , 'between:25,250'],
            'new_weight'                => ['nullable' , 'integer' , 'between:1,400'],
            'new_blood_type'            => ['nullable' , new Enum(BloodTypeEnum::class)],
            'new_chronic_diseases'      => ['nullable' , 'string'],
            'new_notes'                 => ['nullable' , 'string'],
            //next reservation
            'next_text'                 => ['nullable' , 'string'],
            'next_notes'                => ['nullable' , 'string'],
            'next_date'                 => ['nullable' , 'date'],
            'time_to_come'              => ['nullable', 'date_format:H:i'],
            //medicines
            'medicines'                 => ['nullable' , 'array'],
            'medicines.*.text'          => ['required' , 'string' , 'max:255'],
            'medicines.*.status'        => ['required' , new Enum(TreatmentStatusEnum::class)],
            'medicines.*.end_date'      => ['nullable' , 'date'],
            'medicines.*.other_end_date'=> ['nullable' , 'string'],
            'medicines.*.days_to_take'  => ['nullable' , new Enum(DaysToTakeEnum::class)],
            //medicine_days
            'medicines.*.days'          => ['nullable' , 'array'],
            'medicines.*.days.*.day_id' => ['required' , 'exists:days,id'],
            //medicine_times
            'medicines.*.days.*.time'           => ['nullable' , 'array'],
            'medicines.*.days.*.time.*'         => ['required' , 'date_format:H:i'],
            'medicines.*.days.*.other_time'     => ['nullable' , 'array'],
            'medicines.*.days.*.other_time.*'   => ['required' , new Enum(MedicineTimeEnum::class)],
            //instruction
            'instructions'                      => ['nullable' , 'array'],
            'instructions.*.text'               => ['required' , 'string' , 'max:255'],
            'instructions.*.status'             => ['required' , new Enum(TreatmentStatusEnum::class)],
            'instructions.*.end_date'           => ['nullable' , 'date'],
            'instructions.*.other_end_date'     => ['nullable' , 'string'],
            'instructions.*.notes'              => ['nullable' , 'string'],
        ];
    }
}
