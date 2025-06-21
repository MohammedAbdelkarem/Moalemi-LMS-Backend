<?php

namespace App\Http\Requests\Doctor;

use Illuminate\Validation\Rule;
use App\Http\Requests\BaseApiRequest;

class UpdateDoctorRequest extends BaseApiRequest
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
        $id = $this->route('doctor');

        return [
            "clinic_name" => ['required' , Rule::unique('doctors' , 'clinic_name')->ignore($id) , 'max:255'],
            "address_text" => ['required' , 'max:255'],
            "lat" => ['required'],
            "lng" => ['required'],
            "license_number" => ['required' , Rule::unique('doctors' , 'license_number')->ignore($id)],
            "is_center" => ['required' , 'boolean'],
            "bio" => ['required' , 'max:255'],
            "join_reason" => ['required' , 'max:255'],
            //update in separated api the images
            "logo" => [
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
            //specialization table
            "sub_category_ids" => ['required' , 'array'],
            "sub_category_ids.*" => ['required' , 'exists:sub_categories,id'],
        ];
    }
}
