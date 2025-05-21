<?php

namespace App\Http\Requests\System\CustomerServiceCard;

use App\Enums\CustomerServiceCard\CustomerServiceCardStatus;
use App\Exceptions\ApiException;
use App\Http\Requests\BaseApiRequest;
use App\Models\System\CustomerService\CustomerServiceMessage;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class CustomerServiceMessageRequest extends BaseApiRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return match ($this->route()->getActionMethod()) {
            "store" => $this->storeRules(),
        };
    }

    public function prepareForValidation()
    {
        //Check Time Between Customer Service Card Messages Creation

        if (auth()->user() && auth()->user()->role_id == 3)
            if ($message = CustomerServiceMessage::where(["user_id" => auth()->id(), "card_id" => $this->card_id])
                ->where("created_at", ">", Carbon::now()->subSeconds(config("_custom.time_between_two_serveic_messages")))
                ->latest()->first()
            )
                throw new ApiException(
                    message: trans(
                        "exception_messages.time_store_customer_card_message",
                        ["seconds" => (int) Carbon::now()->diffInSeconds(Carbon::parse($message->created_at)->addMinutes($message->created_at)->addSeconds(config("_custom.time_between_two_serveic_messages")))]
                    ),
                    statusCode: 400,
                );
    }

    public function storeRules()
    {
        //To Check If user can send message to card or not
        //Admin
        $exists = Rule::exists("customer_cards", "id");

        //Check User
        if (auth()->user()->role_id == 3) {
            $exists = Rule::exists("customer_cards", "id")
                ->whereNull('deleted_at')
                ->where("user_id", auth()->id())
                ->whereNot("status", CustomerServiceCardStatus::CLOSED->value);
        }

        return [
            "card_id" => ["required", $exists],
            "message" => ["required", "string", "between:1,2500"],
        ];
    }

    public function messages()
    {
        return [];
    }
}
