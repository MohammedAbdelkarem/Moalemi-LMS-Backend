<?php

namespace App\Http\Requests\Transaction;

use Illuminate\Validation\Rule;
use App\Http\Requests\BaseApiRequest;

class CreateContextCuponRequest extends BaseApiRequest
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
            'context_id' => ['required', 'integer'],
            'context_type' => ['required', 'string', Rule::in('Course', 'Subject', 'Unit')],
            'expired_at' => ['required', 'date'],
        ];
    }
}
