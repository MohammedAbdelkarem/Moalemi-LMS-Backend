<?php

namespace App\Http\Requests\Quiz;

use App\Http\Requests\BaseApiRequest;
use Illuminate\Foundation\Http\FormRequest;

class SubmitAnswerRequest extends BaseApiRequest
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
            'quiz_result_id' => ['required', 'integer', 'exists:quiz_results,id'],
            'question_id' => ['required', 'integer', 'exists:questions,id'],
            'answer_id' => ['required_without:answer_ids', 'integer', 'exists:answers,id'],
            'answer_ids' => ['required_without:answer_id', 'array', 'min:1'],
            'answer_ids.*' => ['integer', 'exists:answers,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'answer_id.required_without' => 'Either answer_id or answer_ids must be provided',
            'answer_ids.required_without' => 'Either answer_id or answer_ids must be provided',
        ];
    }
}
