<?php

namespace App\Services\Shift;

use App\Models\Shift;
use App\Constants\ExceptionMessages;
use App\Http\Resources\ShiftResource;

/**
 * Class ShiftService.
 */
class ShiftService
{
    public function getDoctorShifts($doctorId)
    {
        $shifts = Shift::with('day')
            ->where('doctor_id', $doctorId)
            ->orderBy('day_id') // Ensure ordering by day
            ->get()
            ->groupBy('day_id');

        $formattedShifts = [];

        foreach ($shifts as $dayId => $dayShifts) {
            $formattedShifts[] = [
                'day' => $dayShifts->first()->day->name,
                'shifts' => ShiftResource::collection($dayShifts),
            ];
        }

        return $formattedShifts;
    }
    public function show($id)
    {
        return Shift::findByIdOrFail($id)->with('day');
    }
    public function storeShifts($inputShifts)
    {
        //check for overlaps with existing shifts in the database
        $this->checkExistingShifts($inputShifts);

        // If no overlaps, proceed to store the shifts
        foreach ($inputShifts['shift_times'] as $shift) 
        {
            Shift::create([
                'day_id' => $shift['day_id'],
                'start_time' => $shift['start_time'],
                'end_time' => $shift['end_time'],
                'doctor_id' => doctor_id()
            ]);
        }
    }

    public function updateShift($data , $id)
    {
        $array['shift_times'] = $data;
        //check for overlaps with existing shifts in the database
        $this->checkExistingShifts($array , $id);

        // If no overlaps, proceed to store the shift
        
        $shift = Shift::findByIdOrFail($id);

        $shift->update([
           'start_time' => $data['start_time'],
           'end_time' => $data['end_time'], 
           'day_id' => $data['day_id'], 
           'doctor_id' => doctor_id()
        ]);
    }

    public function deleteShift($id)
    {
        $shift = Shift::findByIdOrFail($id);

        // if($shift->comingReservations())
        //     return forbiddenFailure(null , ExceptionMessages::MSG_CAN_NOT_DELETE_SHIFT_CUZ_RESERVATIONS_EXISTS);

        $shift->delete();
    }

    private function checkExistingShifts($inputShifts , $shiftId = null)
    {
        foreach ($inputShifts['shift_times'] as $shift) 
        {
            if (!isset($shift['day_id'], $shift['start_time'], $shift['end_time'])) 
            {
                continue;
            }

            $dayId = $shift['day_id'];
            
            $start = strtotime($shift['start_time']);
            $end = strtotime($shift['end_time']);

            // Fetch existing shifts for the day from the database
            $query = Shift::where('day_id', $dayId)
                        ->where('doctor_id', doctor_id());

            // If a shift ID is provided, exclude it from the query
            if ($shiftId !== null) 
            {
                $query->where('id', '!=', $shiftId);
            }

            $existingShifts = $query->get();

            foreach ($existingShifts as $existingShift)
            {
                $existingStart = strtotime($existingShift->start_time);
                $existingEnd = strtotime($existingShift->end_time);

                // Check if times overlap
                if (($start < $existingEnd && $end > $existingStart)) 
                {
                    return unprocessableFailure([] , ExceptionMessages::MSG_SHIFTS_OVERLAPPING);
                }
            }
        }
    }
}
