<?php

use Carbon\Carbon;

if (!function_exists('BMI')) {
    function BMI($weight , $height)
    {
        return $weight / pow(cmToM($height) , 2);
    }
}

if (!function_exists('cm_to_m')) {
    function cmToM($height)
    {
        return $height / 100;
    }
}

if (!function_exists('water_goal')) {
    function water_goal($weight , $is_male , $birth_date)
    {
        $age = age($birth_date);

        $factorial = 0;

        if($is_male)
        {
            $factorial = ($age >= 30) 
            ? 0.3
            : 0.5;
        }
        else
        {
            $factorial = ($age >= 30) 
            ? 0.2
            : 0.3;
        }

        return $weight * 0.03 + $factorial;
    }
}

if (!function_exists('sleep_goal')) {
    function sleep_goal($birth_date)
    {
        $age = age($birth_date);
        
        $goal = 0;
        
        if($age < 18)
            $goal = 9;
        else if($age >= 18 && $age <= 64)
            $goal = 8;
        else 
            $goal = 7;

        return $goal;
    }
}

if (!function_exists('distance')) {
    function distance($steps)
    {
        return ($steps * 0.75) / 1000;
    }
}

if (!function_exists('calories')) {
    function calories($steps)
    {
        return $steps * 0.05;
    }
}

if (!function_exists('age')) {
    function age($birth_date)
    {
        return Carbon::parse($birth_date)->age;
    }
}