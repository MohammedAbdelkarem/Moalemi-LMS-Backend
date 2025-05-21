<?php

namespace App\Services\System\CustomerServiceCard;

use App\Constants\NotificationMessages;
use App\Constants\Resources;
use App\Enums\CustomerServiceCard\CustomerServiceCardStatus;
use App\Enums\CustomerServiceCard\CustomerServiceCardTypes;
use App\Enums\Notifications\NotificationScreens;
use App\Enums\Notifications\NotificationTypes;
use App\Models\System\CustomerService\CustomerServiceCard;
use App\Services\MainService;
use Illuminate\Support\Facades\DB;

/**
 * Class CustomerServiceCardService.
 */
class CustomerServiceCardService extends MainService
{
    public function getTypesStatus()
    {
        return [
            "types" => CustomerServiceCardTypes::transValues(),
            "status" => CustomerServiceCardStatus::transValues(),
        ];
    }

    public function indexUser($per_page, $search, $type = null, $status = null, bool $my)
    {
        $user = auth()->user();

        return CustomerServiceCard::query()
            // ->where("user_id", auth()->id()) //TODO:TEMPLATE IF user can only see his customer cards
            ->when($status, function ($query) use ($status) {
                $query->where("status", $status);
            })
            ->when($type, function ($query) use ($type) {
                $query->where("type", $type);
            })
            ->when($search, function ($query) use ($search) {
                $query->whereAny(['title', 'description'], 'like', '%' . strtolower($search) . '%');
            })
            ->withCount("messages")
            ->orderByDesc("created_at")
            ->paginate($per_page);
    }

    public function indexAdmin($per_page = 10, $search, $type = null, $status = null)
    {
        return CustomerServiceCard::query()->withTrashed()
            ->when($status, function ($query) use ($status) {
                $query->where("status", $status);
            })
            ->when($type, function ($query) use ($type) {
                $query->where("type", $type);
            })
            ->when($search, function ($query) use ($search) {
                $query->whereAny(['title', 'description'], 'like', '%' . strtolower($search) . '%');
            })
            ->with(["user" => function ($query) {
                $query->withTrashed()->with('role');
            }])
            ->withCount("messages")
            ->orderByDesc("created_at")
            ->paginate($per_page);
    }

    public function store($validatedData)
    {
        CustomerServiceCard::create([
            "user_id"       => auth()->id(),
            "title"         => $validatedData["title"],
            "description"   => $validatedData["description"],
            "type"          => $validatedData["type"],
            "status"        => CustomerServiceCardStatus::PENDING->value,
        ]);
    }

    public function showUser($id)
    {
        return CustomerServiceCard::query()
            // ->where("user_id", auth()->id()) //TODO:TEMPLATE IF user can only see his customer cards
            ->withCount("messages")
            ->with(["user" => function ($query) {
                $query->withTrashed();
            }])
            ->findOrFail($id);
    }

    public function showAdmin($id)
    {
        return CustomerServiceCard::withTrashed()
            ->with(["user" => function ($query) {
                $query->withTrashed();
            }])
            ->withCount("messages")
            ->findOrFail($id);
    }

    public function update($id, $validatedData)
    {
        $card = findByIdOrFail(CustomerServiceCard::class, $id, Resources::CARD, 'female', asQuery: true);
        if (auth()->user()->role_id == 3)
            $card->whereNot("status", CustomerServiceCardStatus::CLOSED->value)->where("user_id", auth()->id());

        $card = $card->firstOrFail();
        $card->update([
            "title"        => $validatedData["title"],
            "description"  => $validatedData["description"],
            "type"         => $validatedData["type"],
        ]);
    }

    public function close($id)
    {
        $card = findByIdOrFail(CustomerServiceCard::class, $id, Resources::CARD, 'female');
        $card->status = CustomerServiceCardStatus::CLOSED->value;
        $card->save();

        $this->sendDirectNotification(
            targeted_user_id: $card->user_id,
            title: $this->notificationMessage(NotificationMessages::CUSTOMER_SERVICE_CARD_CLOSE_TITLE),
            body: $this->notificationMessage(NotificationMessages::CUSTOMER_SERVICE_CARD_CLOSE_BODY, ["name" => $card->title]),
            type: NotificationTypes::ACCOUNT->value,
            createdBy: auth()->id(),
            page: NotificationScreens::HOME->value,
            local: $card->user->language,
        );
    }

    public function destroy($id) //Soft delete
    {
        findByIdOrFail(
            CustomerServiceCard::class,
            $id,
            Resources::CARD,
            'female',
            ["user_id" => auth()->id()]
        )->delete();
    }

    public function destroyByAdmin($id)
    {
        $card = findByIdOrFail(
            CustomerServiceCard::class,
            $id,
            Resources::CARD,
            'female',
        );

        $card->delete();

        $this->sendDirectNotification(
            targeted_user_id: $card->user_id,
            title: $this->notificationMessage(NotificationMessages::CUSTOMER_SERVICE_CARD_DELETE_TITLE),
            body: $this->notificationMessage(NotificationMessages::CUSTOMER_SERVICE_CARD_DELETE_BODY, ["name" => $card->title]),
            type: NotificationTypes::ACCOUNT->value,
            createdBy: auth()->id(),
            page: NotificationScreens::HOME->value,
            local: $card->user->language,
        );
    }
}