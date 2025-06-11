<?php

namespace App\Http\Requests\Reservation;

use App\Enums\RejectionReasonEnum;
use App\Http\Requests\BaseApiRequest;
use Illuminate\Validation\Rules\Enum;

class RejectByAdminRequest extends BaseApiRequest
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
            'other_rejection_reason' => ['required_without:rejection_reason' , 'string'],
            'rejection_reason' => ['required_without:other_rejection_reason' , new Enum(RejectionReasonEnum::class)],
        ];
    }
}
