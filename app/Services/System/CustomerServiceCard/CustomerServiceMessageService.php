<?php

namespace App\Services\System\CustomerServiceCard;

use App\Constants\Resources;
use App\Enums\CustomerServiceCard\CustomerServiceCardStatus;
use App\Http\Resources\System\CustomerServiceCard\CustomerServiceMessageResource;
use App\Models\System\CustomerService\CustomerServiceMessage;
use App\Services\MainService;
use Illuminate\Support\Facades\DB;

/**
 * Class CustomerServiceMessageService.
 */
class CustomerServiceMessageService extends MainService
{
    public function index($per_page, $card_id)
    {
        $user = auth()->user();
        $messages = CustomerServiceMessage::query()
            ->where("card_id", $card_id);

        //Check for card privacy
        //TODO:TEMPLATE IF user can only see his customer cards
        // if ($user && $user->role_id == 3)
        //     $messages->whereHas("card", function ($query) {
        //         $query->where("user_id", auth()->id());
        //     });

        //Get The Data
        return $messages
            ->with("user", function ($query) {
                $query->withTrashed();
            })
            ->orderBy("created_at", "desc")
            ->paginate($per_page);
    }

    public function store($validatedData)
    {
        //Create the message
        $message = CustomerServiceMessage::create([
            "user_id" => auth()->id(),
            "card_id" => $validatedData["card_id"],
            "message" => $validatedData["message"],
        ]);

        $card = $message->card;

        if (
            $card &&
            auth()->user()->role_id != 3    //User Is Admin
            && $card->status == CustomerServiceCardStatus::PENDING->value   //Card is Pending
        ) {
            $card->status = CustomerServiceCardStatus::OPEN->value; //Open The Card
            $card->save();
        }
        //Return Message Instance
        return new CustomerServiceMessageResource($message);
    }

    //Only Admin
    public function destroy($id)
    {
        findByIdOrFail(CustomerServiceMessage::class, $id, Resources::MESSAGE, 'female')->delete();
    }
}