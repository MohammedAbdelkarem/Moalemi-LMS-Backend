<?php

namespace App\Http\Requests\System\Info;

use App\Enums\AppTypes;
use App\Http\Requests\BaseApiRequest;
use CodeZero\UniqueTranslation\UniqueTranslationRule;
use Illuminate\Validation\Rule;

class FaqCategoryRequest extends BaseApiRequest
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
            "name"   => ["required", "array"],
            "name.*" => ["required", "string", "max:255", UniqueTranslationRule::for("faq_categories", "name")],
            "app"    => ["required", Rule::in(AppTypes::values())],
        ];
    }

    public function updateRules()
    {
        return [
            "name"      => ["required", "array"],
            "name.*"    => ["required", "string", "max:255", UniqueTranslationRule::for("FAQ", "question")->ignore($this->id)],
            "app"       => ["required", Rule::in(AppTypes::values())],
        ];
    }

    public function messages()
    {
        return [];
    }
}
