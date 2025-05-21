<?php

namespace App\Services\Administration\Profile;

use App\Constants\ExceptionMessages;
use App\Constants\Resources;
use App\Exceptions\ApiException;
use App\Models\Administration\Profile\AdminProfile;
use App\Models\User;
use App\Models\Users\Profile\UserDevice;
use App\Services\JWTTokensService;
use App\Services\MainService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminProfileService extends MainService
{
    public function __construct(
        protected JWTTokensService $jwtService,
    ) {}

    public function adminSugs($search)
    {
        return User::query()->select(["id", "role_id", "name", "email", "avatar", "deleted_at", "deactive_at", "created_at"])
            ->whereNot("role_id", 3)
            ->when(
                $search,
                function ($q) use ($search) {
                    $q->searchName($search);
                }
            )->with('archivedAccount')->limit(8)->get();
    }

    /**
     * @param mixed $active_status  1 => All | 2 => Active | 3 => Inactive
     */
    public function index(
        $per_page,
        $search,
        $cities,
        $role_id,
        $active_status,
        $start_date,
        $end_date,
    ) {
        return User::query()
            ->whereNot('role_id', 3)
            ->when($role_id, function ($query) use ($role_id) {
                $query->where('role_id', $role_id);
            })
            ->whereNotNull(['name', 'account_verified_at'])
            //Active Status
            ->when($active_status, function ($query) use ($active_status) {
                if ($active_status == "2")
                    $query->whereNull('deactive_at');
                elseif ($active_status == "3")
                    $query->whereNotNull('deactive_at');
            })
            //Search By name or phone number
            ->when($search, function ($query) use ($search) {
                $query->searchName($search);
            })
            //Filter By Cities
            ->when($cities, function ($query) use ($cities) {
                $query->whereIn('city_id', decodeStringToArray($cities));
            })
            //Filter By Join Date (min)
            ->when($start_date, function ($query) use ($start_date) {
                $query->where('created_at', ">=", $start_date);
            })
            // Filter By Join Date (max)
            ->when($end_date, function ($query) use ($end_date) {
                $query->where('created_at', "<=", $end_date);
            })
            //Get Other Need Info
            ->with(['city', 'archivedAccount'])
            ->paginate($per_page);
    }

    public function storeAdmin($validatedData)
    {
        //Create User (Admin)
        $user = User::create([
            "role_id"       => $validatedData["role_id"],
            "name"          => $validatedData["name"],
            "birth_date"    => $validatedData["birth_date"],
            "is_male"       => $validatedData["is_male"],
            "email"         => $validatedData["email"],
            "language"      => "ar",
            "phone_number"  => $validatedData["phone_number"],
            "city_id"       => $validatedData["city_id"],
            "active_notifications"  => true,
            "account_verified_at"   => Carbon::now()->format("Y-m-d H:i:s"),
        ]);
        //Create Admin Profile
        AdminProfile::create([
            "user_id"       => $user->id,
            "password"      => Hash::make($validatedData["password"]),
            "created_by"    => auth()->id(),
        ]);
        //Store User Image
        if (isset($validatedData["avatar"])) {
            $user->avatar = $this->storeFile(
                file: $validatedData["avatar"],
                path: "users/{$user->id}"
            );
            $user->save();
        }
    }

    public function show($id)
    {
        return findByIdOrFail(
            model: User::class,
            modelId: $id,
            resource: Resources::RES_USER,
            type: 'male',
            with: ['city', 'archivedAccount'],
            withTrashed: true,
            asQuery: true,
        )->whereNot("role_id", 3)
            ->with('adminProfile.creator')
            ->findOrFail($id);
    }

    public function updateAdmin($validatedData, $id)
    {
        /**
         * @var \App\Models\User $user
         */
        $user = findByIdOrFail(User::class, $id, Resources::RES_USER);

        //Delete Tokens so user have to login again to get the new abilities
        if ($user->role_id != $validatedData["role_id"]) {
            $this->jwtService->InvalidateAllTokensByUserID($user->id);
            UserDevice::where('user_id', $user->id)->delete();
        }
        //Create User (Admin)
        $user->update([
            "role_id"       => $validatedData["role_id"],
            "name"          => $validatedData["name"],
            "birth_date"    => $validatedData["birth_date"],
            "is_male"       => $validatedData["is_male"],
            "email"         => $validatedData["email"],
            "phone_number"  => $validatedData["phone_number"],
            "city_id"       => $validatedData["city_id"],
        ]);
        //Update User Image
        $this->updateProfileImage($validatedData, $id);

        if (!empty($validatedData["password"]))
            $user->adminProfile()->update([
                "password"      => Hash::make($validatedData["password"]),
            ]);
    }

    public function updateProfileImage($validatedData, $id): array
    {
        /**
         * @var \App\Models\User $user
         */
        $user = findByIdOrFail(User::class, $id, Resources::RES_USER);

        $user = $this->StoreUpdate(
            file: $validatedData["avatar"],
            path: "users/{$user->id}",
            model: $user,
            column: "avatar",
            deleteImage: $validatedData["delete_image"],
            singleFilePath: $user->avatar ?? ""
        );

        $user->save();

        //return image url
        return ["img" => $this->getProfileImage($user)];
    }

    public function deactivateAccount($id)
    {
        $user = findByIdOrFail(User::class, $id, Resources::RES_USER);

        /**
         * To not deactive user 1 OR a super admin from other admin || or normal user
         */
        if (($user->role_id == 1 && $user->id != auth()->id()) || $user->id == 1 || $user->role_id == 3) {
            throw new ApiException(null, trans(ExceptionMessages::MSG_ACCEESS_DENIED), 400);
        }

        $user->deactive_at ?
            $user->deactive_at = null
            : $user->deactive_at = Carbon::now()->format('Y-m-d H:i:s');
        $user->save();

        $this->jwtService->InvalidateAllTokensByUserID($user->id);

        UserDevice::where('user_id', $user->id)->delete();
        return (bool) $user->deactive_at;
    }

    public function changeLang($validatedData)
    {
        /**
         * @var \App\Models\User $user
         */
        $user = auth()->user();

        $user->language = $validatedData["lang"];
        $user->save();
    }

    public function changeNotificationStatus()
    {
        /**
         * @var \App\Models\User $user
         */
        $user = auth()->user();

        $user->active_notifications = !$user->active_notifications;
        $user->save();
    }
}
