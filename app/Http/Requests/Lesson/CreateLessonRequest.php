<?php

namespace App\Http\Requests\Lesson;

use App\Enums\PublishStatusEnum;
use Illuminate\Validation\Rule;
use App\Http\Requests\BaseApiRequest;
use Illuminate\Foundation\Http\FormRequest;

class CreateLessonRequest extends BaseApiRequest
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
            'e_level_id' => ['required', 'exists:e_levels,id'],
            'c_level_id' => ['required', 'exists:c_levels,id'],
            'course_id' => ['required', 'exists:courses,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'unit_id' => ['required', 'exists:units,id'],
            'sub_unit_id' => ['required', 'exists:sub_units,id'],
            'name' => ['required', 'string', 'min:2', 'max:255', Rule::unique('lessons')->where('sub_unit_id', request('sub_unit_id'))],
            'bio' => ['sometimes', 'string'],
            // 'publish_status' => ['sometimes', Rule::in(PublishStatusEnum::values())],
            'images' => [
                'sometimes',
                'array',
                'min:1',
                'max:10' // Limit to 10 images per lesson
            ],
            'images.*' => [
                'required_with:images',
                'mimes:jpeg,jpg,png,webp',
                'max:4096'
            ],
            'video' => [
                'sometimes',
                'mimes:mp4,webm,mov,avi',
                'max:102400' // 100MB max for video
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'images.max' => 'You can upload maximum 10 images per lesson.',
            'images.*.max' => 'Each image must be less than 4MB.',
            'video.max' => 'Video must be less than 100MB.',
        ];
    }
}
