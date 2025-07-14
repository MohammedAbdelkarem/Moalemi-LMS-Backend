<?php

namespace App\Http\Requests\Users\Auth;

use App\Rules\PhoneNumberRule;
use App\Rules\ShiftsOverlappingRule;
use Illuminate\Validation\Rule;
use App\Http\Requests\BaseApiRequest;

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
            "registerDoctor" => $this->registerDoctorRules(),
            "loginDoctor" => $this->loginDoctorRules(),
            "loginPatient" => $this->loginPatientRules(),
        };
    }

    public function registerDoctorRules()
    {
        return [
            //users table
            "name" => ['required' , 'string'],
            "phone_number" => ['required', new PhoneNumberRule() , Rule::unique('users' , 'phone_number')->where('role_id' , 3)],
            "email" => ['email' , 'required' , Rule::unique('users' , 'email')->where('role_id' , 3)],
            "birth_date" => ['required' , 'date'],
            "is_male" => ['required' , 'boolean'],
            "avatar" => [
                'nullable',
               'mimes:jpeg,jpg,png,webp',
               'max:4096'
            ],
            "city_id" => ['required' , 'exists:cities,id'],
            //doctors table
            "clinic_name" => ['required' , Rule::unique('doctors' , 'clinic_name') , 'max:255'],
            "address_text" => ['required' , 'max:255'],
            "lat" => ['required'],
            "lng" => ['required'],
            "license_number" => ['required' , Rule::unique('doctors' , 'license_number')],
            "is_center" => ['required' , 'boolean'],
            "bio" => ['required' , 'max:255'],
            "join_reason" => ['required' , 'max:255'],
            "logo" => [
                'nullable',
               'mimes:jpeg,jpg,png,webp',
               'max:4096'
            ],
            "cover_image" => [
                'nullable',
               'mimes:jpeg,jpg,png,webp',
               'max:4096'
            ],
            "certificates" => ['nullable' , 'array'],
            "certificates.*.image" => [
                'required',
               'mimes:jpeg,jpg,png,webp',
               'max:4096'
            ],
            "certificates.*.title" => ['required','max:255'],
            //phone numbers table
            "phone_numbers" => ['nullable' , 'array'],
            "phone_numbers.*" => ['required'],
            //specialization table
            "sub_category_ids" => ['required' , 'array'],
            "sub_category_ids.*" => ['required' , 'exists:sub_categories,id'],
            //shifts table
            "shift_times"       => ['required' , 'array', new ShiftsOverlappingRule()],
            "shift_times.*.day_id" => ['required' , 'exists:days,id'],
            "shift_times.*.start_time" => ['required', 'date_format:H:i'],
            "shift_times.*.end_time" => ['required', 'date_format:H:i', 'after:shift_times.*.start_time'],
            //subscriptions table
            "plan_id" => ['required', 'exists:plans,id'],
            "has_been_paid" => ['required', 'boolean'],
        ];
    }
    public function loginDoctorRules()
    {
        return [
             "phone_number" => ['required', new PhoneNumberRule() , Rule::exists('users' , 'phone_number')->where('role_id' , 3)],
        ];
    }
    public function loginPatientRules()
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
