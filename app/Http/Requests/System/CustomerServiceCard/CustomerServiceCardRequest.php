<?php

namespace App\Http\Requests\System\CustomerServiceCard;

use App\Enums\CustomerServiceCard\CustomerServiceCardTypes;
use App\Exceptions\ApiException;
use App\Http\Requests\BaseApiRequest;
use App\Models\System\CustomerService\CustomerServiceCard;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class CustomerServiceCardRequest extends BaseApiRequest
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

    public function prepareForValidation()
    {
        //Check Time Between Customer Service Cards Creation
        if ($this->route()->getActionMethod() == "store")
            if ($card = CustomerServiceCard::withTrashed()
                ->where("user_id", auth()->id())
                ->where("created_at", ">", Carbon::now()->subMinutes(config("_custom.time_between_store_service_cards")))
                ->latest()->first()
            )
                throw new ApiException(
                    message: trans(
                        "exception_messages.time_store_customer_card",
                        ["minutes" => Carbon::now()->diffInMinutes(Carbon::parse($card->created_at)->addMinutes(config("_custom.time_between_store_service_cards")))]
                    ),
                    statusCode: 400,
                );
    }

    public function storeRules()
    {
        return [
            "title"         => ["required", "string", "between:5,255"],
            "description"   => ["required", "string", "between:5,3000"],
            "type"          => ["required", Rule::in(CustomerServiceCardTypes::values())],
        ];
    }

    public function updateRules()
    {
        return [
            "title"         => ["required", "string", "between:5,255"],
            "description"   => ["required", "string", "between:5,3000"],
            "type"          => ["required", Rule::in(CustomerServiceCardTypes::values())],
        ];
    }

    public function messages()
    {
        return [];
    }
}