<?php

namespace App\Http\Requests\MedicalProfile;

use App\Enums\TreatmentStatusEnum;
use App\Http\Requests\BaseApiRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateInstructionRequest extends BaseApiRequest
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
            //instruction
            'text'               => ['required' , 'string' , 'max:255'],
            'status'             => ['required' , new Enum(TreatmentStatusEnum::class)],
            'end_date'           => ['nullable' , 'date'],
            'other_end_date'     => ['nullable' , 'string'],
            'notes'              => ['nullable' , 'string'],
        ];
    }
}
