<?php

namespace App\Http\Requests\Users\Auth;

use App\Http\Requests\BaseApiRequest;
use App\Rules\PhoneNumberRule;

class AuthRequest extends BaseApiRequest
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
        return match ($this->route()->getActionMethod()) {
            "login" => $this->loginRules(),
        };
    }

    public function loginRules()
    {
        return [
            "phone_number" => ['required', new PhoneNumberRule()],
        ];
    }

    public function messages()
    {
        return [];
    }
}
