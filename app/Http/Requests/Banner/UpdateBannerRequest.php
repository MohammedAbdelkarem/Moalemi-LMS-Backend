<?php

namespace App\Http\Requests\Banner;

use App\Enums\LevelEnum;
use Illuminate\Validation\Rule;
use App\Http\Requests\BaseApiRequest;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBannerRequest extends BaseApiRequest
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
            'title' => ['required', 'string', 'min:4' , 'max:255'],
            'description' => ['present' , 'nullable', 'string'],
            //can specify the rules for the id and the type depending on the project(exist , unique , ....)
            'bannerable_id' => ['present' ,'nullable', 'min:1' , 'required_without:external_link' , 'required_with:bannerable_type'],
            'bannerable_type' => ['present' ,'nullable' , 'required_without:external_link' , 'required_with:bannerable_id'],
            'external_link' => ['present' ,'nullable' , 'max:255' , 'required_without:bannerable_id' , 'required_without:bannerable_type'],
            // 'image'                      => [
            //     'required',
            //     'mimes:jpeg,jpg,png,webp',
            //     'max:4096'
            // ],
        ];
    }
}
