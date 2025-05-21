<?php

namespace App\Http\Requests\System\Info;

use App\Http\Requests\BaseApiRequest;
use CodeZero\UniqueTranslation\UniqueTranslationRule;

class FAQRequest extends BaseApiRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return match ($this->route()->getActionMethod()) {
            "store"  => $this->storeRules(),
            "update" => $this->updateRules(),
        };
    }

    public function storeRules()
    {
        return [
            "question"      => ["required", "array"],
            "question.*"    => ["required", "string", "max:255", UniqueTranslationRule::for("FAQ", "question")],
            "answer"        => ["required", "array"],
            "answer.*"      => ["required", "string", "max:2000"],
            "category_id"   => ["required", "exists:faq_categories,id"],
            "is_draft"      => ['required', 'boolean'],
        ];
    }

    public function updateRules()
    {
        return [
            "question"      => ["required", "array"],
            "question.*"    => ["required", "string", "max:255", UniqueTranslationRule::for("FAQ", "question")->ignore($this->id)],
            "answer"        => ["required", "array"],
            "answer.*"      => ["required", "string", "max:2000"],
            "category_id"   => ["required", "exists:faq_categories,id"],
            "is_draft"      => ['required', 'boolean'],
        ];
    }

    public function messages()
    {
        return [];
    }
}
