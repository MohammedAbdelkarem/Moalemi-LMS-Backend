<?php

namespace App\Services\System\Info;

use App\Constants\Resources;
use App\Models\System\Info\City;
use App\Services\MainService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

/**
 * Class CityService.
 */
class CityService extends MainService
{
    public function index($per_page, $search = null)
    {
        $cities = City::query()
            ->when($search, function (Builder $query) use ($search) {
                $query->whereAny(['name_en', 'name_ar'], 'like', strtolower($search) . '%');
            })->orderBy("name_" . (app()->getLocale()));

        return $per_page > 0 ? $cities->paginate($per_page) : $cities->get();
    }

    public function store($validatedData)
    {
        City::create([
            "name_ar" => $validatedData["name_ar"],
            "name_en" => $validatedData["name_en"],
        ]);
    }

    public function show($id)
    {
        return findByIdOrFail(City::class, $id, Resources::CITY, 'female');
    }

    public function update($validatedData, $id)
    {
        $city = findByIdOrFail(City::class, $id, Resources::CITY, 'female');
        $city->name_ar = $validatedData["name_ar"];
        $city->name_en = $validatedData["name_en"];
        $city->save();
    }

    public function destroy($id)
    {
        $city = City::withCount('users')->findOrFail($id);
        $city->users_count > 0 ? false : $city->delete();
    }
}