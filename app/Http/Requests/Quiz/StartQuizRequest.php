<?php

namespace App\Http\Requests\Quiz;

use App\Http\Requests\BaseApiRequest;
use Illuminate\Foundation\Http\FormRequest;

class StartQuizRequest extends BaseApiRequest
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
            'quiz_id' => ['required', 'integer', 'exists:quizzes,id'],
            'student_id' => ['required', 'integer', 'exists:users,id'],
        ];
    }
}
