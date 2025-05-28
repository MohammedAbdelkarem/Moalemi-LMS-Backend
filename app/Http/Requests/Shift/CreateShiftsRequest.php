<?php

namespace App\Http\Requests\Shift;

use App\Rules\ShiftsOverlappingRule;
use App\Http\Requests\BaseApiRequest;

class CreateShiftsRequest extends BaseApiRequest
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
            "shift_times"       => ['required' , 'array', new ShiftsOverlappingRule()],
            "shift_times.*.day_id" => ['required' , 'exists:days,id'],
            "shift_times.*.start_time" => ['required', 'date_format:H:i'],
            "shift_times.*.end_time" => ['required', 'date_format:H:i', 'after:shift_times.*.start_time'],
        ];
    }
}
