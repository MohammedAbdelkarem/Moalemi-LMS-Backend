<?php

namespace App\Http\Requests\Doctor\PhoneNumbers;

use Illuminate\Validation\Rule;
use App\Http\Requests\BaseApiRequest;

class UpdatePhoneNumbersRequest extends BaseApiRequest
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
        $id = $this->route('phone_number');
        
        return [
            'phone_number' => ['required' , 'string' , 'max:14' , Rule::unique('doctor_phone_numbers' , 'phone_number')->ignore($id)],
        ];
    }
}
