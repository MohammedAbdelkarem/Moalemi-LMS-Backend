<?php

namespace App\Services\ScreenShot;

use App\Models\ScreenShot;
use App\Constants\ExceptionMessages;

/**
 * Class ScreenShotService.
 */
class ScreenShotService
{
    public function create($data)
    {
        ScreenShot::create($data);
    }

    public function disable($id)
    {
        $screenShot = ScreenShot::findByIdOrFail($id);

        $screenShot->update([
            'is_disabled' => 1
        ]);
    }

    public function getByUserId($user_id, $data)
    {
        return getOrPaginate(
            ScreenShot::where('user_id', $user_id)
            ->orderBy('created_at', 'desc'),
            $data
        );
    }

    public function takeShot($number_of_shots)
    {
        $this->checkIfAbleToTakeShots(auth()->id());

        $screenShot = ScreenShot::where('user_id', auth()->id())
            ->whereColumn('total_number_used', '<', 'total_number_allowed')
            ->notDisabled()
            ->first();

            
        $screenShot->total_number_used += $number_of_shots;
        $screenShot->save();
    }

    public function getAvailableNumberOfShots($user_id)
    {
        $availableObject = ScreenShot::where('user_id', $user_id)
            ->whereColumn('total_number_used', '<', 'total_number_allowed')
            ->notDisabled()
            ->first();


        $value =  (! $availableObject)
            ? 0
            : $availableObject->total_number_allowed - $availableObject->total_number_used;

        return [
            'available_number_of_shots' => $value,
        ];
    }

    private function checkIfAbleToTakeShots($user_id)
    {
        if(! able_to_take_shots($user_id))
            return forbiddenFailure([] , ExceptionMessages::MSG_CAN_NOT_TAKE_SHOTS);
    }
}
