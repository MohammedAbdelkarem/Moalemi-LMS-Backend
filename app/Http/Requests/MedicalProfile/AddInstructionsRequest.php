<?php

namespace App\Http\Requests\MedicalProfile;

use App\Enums\TreatmentStatusEnum;
use App\Http\Requests\BaseApiRequest;
use Illuminate\Validation\Rules\Enum;

class AddInstructionsRequest extends BaseApiRequest
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
            'instructions'                      => ['required' , 'array'],
            'instructions.*.text'               => ['required' , 'string' , 'max:255'],
            'instructions.*.status'             => ['required' , new Enum(TreatmentStatusEnum::class)],
            'instructions.*.end_date'           => ['nullable' , 'date'],
            'instructions.*.other_end_date'     => ['nullable' , 'string'],
            'instructions.*.notes'              => ['nullable' , 'string'],
        ];
    }
}
