<?php

namespace App\Http\Requests\Plan;

use Illuminate\Validation\Rule;
use App\Http\Requests\BaseApiRequest;

class CreatePlanRequest extends BaseApiRequest
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
            'title' => ['required','string','max:255' , Rule::unique('plans', 'title')],
            'price' => ['required','numeric','min:0'],
            'number_of_days' => ['required','integer','min:1'],
            'discount_percentage' => ['nullable','numeric','min:0','max:100'],
            'discount_start_at' => ['nullable','date','before_or_equal:discount_end_at'],
            'discount_end_at' => ['nullable','date','after_or_equal:discount_start_at'],
        ];
    }
}
