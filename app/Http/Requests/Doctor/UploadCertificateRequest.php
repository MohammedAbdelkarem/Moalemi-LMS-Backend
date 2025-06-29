<?php

namespace App\Http\Requests\Doctor;

use App\Http\Requests\BaseApiRequest;

class UploadCertificateRequest extends BaseApiRequest
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
            "image"       => [
                'required',
               'mimes:jpeg,jpg,png,webp,pdf',
               'max:4096'
            ],
            "title"       => ['nullable','max:255'],
        ];
    }
}
