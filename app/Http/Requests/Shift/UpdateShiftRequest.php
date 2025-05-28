<?php

namespace App\Http\Requests\Shift;

use App\Http\Requests\BaseApiRequest;

class UpdateShiftRequest extends BaseApiRequest
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
            "day_id" => ['required' , 'exists:days,id'],
            "start_time" => ['required', 'date_format:H:i'],
            "end_time" => ['required', 'date_format:H:i', 'after:shift_times.*.start_time'],
        ];
    }
}
