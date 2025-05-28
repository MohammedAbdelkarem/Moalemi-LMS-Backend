<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ShiftsOverlappingRule implements ValidationRule
{
    /**
     * Validate the shifts for overlapping times.
     *
     * @param string $attribute
     * @param mixed $value
     * @param Closure $fail
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $dayShifts = [];

        foreach ($value as $index => $shift) 
        {
            if (!isset($shift['day_id'], $shift['start_time'], $shift['end_time'])) 
            {
                continue;
            }

            $dayId = $shift['day_id'];
            $startTime = $shift['start_time'];
            $endTime = $shift['end_time'];

            // Convert times to comparable format
            $start = strtotime($startTime);
            $end = strtotime($endTime);

            if ($start >= $end) 
            {
                $fail("The end time must be after the start time for shift " . ($index + 1));
                return;
            }

            // Check for overlaps with existing shifts on the same day
            if (isset($dayShifts[$dayId])) 
            {
                foreach ($dayShifts[$dayId] as $existingShift) 
                {
                    $existingStart = strtotime($existingShift['start_time']);
                    $existingEnd = strtotime($existingShift['end_time']);

                    // Check if times overlap
                    if (($start < $existingEnd && $end > $existingStart)) 
                    {
                        $fail("Shift times cannot overlap on the same day. Conflict found between shifts on day {$dayId}.");
                        return;
                    }
                }
            }

            // Add this shift to the day's shifts
            if (!isset($dayShifts[$dayId])) 
            {
                $dayShifts[$dayId] = [];
            }
            $dayShifts[$dayId][] = $shift;
        }
    }
}
