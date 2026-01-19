<?php

namespace App\Http\Requests\ScreenShot;

use Illuminate\Validation\Rule;
use App\Http\Requests\BaseApiRequest;

class ScreenShotRequest extends BaseApiRequest
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
            'user_id' => ['required', Rule::exists('users', 'id')->where('role_id', 5)],
            'total_number_allowed' => ['required', 'integer', 'min:1' , 'max:30'],
        ];
    }
}
