<?php

namespace App\Services\Doctor;

use App\Models\Plan;
use App\Models\Shift;
use App\Models\Doctor;
use App\Models\DoctorPhoneNumber;
use App\Constants\MediaCollection;
use App\Models\Subscription;
use App\Models\Transaction;

/**
 * Class DoctorService.
 */
class DoctorService
{
    public function storeRegisteredDoctor($data)
    {
        //doctor table
        $doctor = Doctor::create([
            'clinic_name' => $data['clinic_name'],
            'address_text' => $data['address_text'],
            'lat' => $data['lat'],
            'lng' => $data['lng'],
            'license_number' => $data['license_number'],
            'is_center' => $data['is_center'],
            'bio' => $data['bio'],
            'join_reason' => $data['join_reason'],
            'user_id' => $data['user_id']
        ]);

        if(isset($data['logo']))
            uploadFileOnMedia($data['logo'] , $doctor , MediaCollection::DOCTOR_LOGO_COLLECTION);
        if(isset($data['cover_image']))
            uploadFileOnMedia($data['cover_image'] , $doctor , MediaCollection::DOCTOR_COVER_COLLECTION);
        if(isset($data['certificates']))
            uploadFilesOnMedia($data['certificates'] , $doctor , MediaCollection::DOCTOR_CERTIFICATES_COLLECTION);
        
        //phone numbers table
        if(isset($data['phone_numbers']))
        {
            foreach($data['phone_numbers'] as $phone_number)
            {
                DoctorPhoneNumber::create([
                    'doctor_id' => $doctor->id,
                    'phone_number' => $phone_number
                ]);
            }
        }
        
        //sub categories table
        $doctor->subCategories()->attach($data['sub_category_ids']);

        //shifts table
        if(isset($data['shift_times']))
        {
            foreach($data['shift_times'] as $shift_time)
            {
                Shift::create([
                    'start_time' => $shift_time['start_time'],
                    'end_time' => $shift_time['end_time'],
                    'day_id' => $shift_time['day_id'],
                    'doctor_id' => $doctor->id
                ]);
            }
        }

        //subscriptions table
        if(isset($data['plan_id']))
        {
            $plan = Plan::find($data['plan_id']);

            $price_after_discount = ($plan->discount_end_at > now()) 
                                        ? $plan->price - ($plan->price * ($plan->discount_percentage / 100)) 
                                        : $plan->price;

            Subscription::create([
               'original_price' => $plan->price,
                'price_after_discount' => $price_after_discount,
                'discount_percentage' => $plan->discount_percentage,
                'start_at' => now(),
                'end_at' => now()->addDays($plan->number_of_days),
                'number_of_days' => $plan->number_of_days,
                'is_active' => 1, 
                'doctor_id' => $doctor->id,
                'plan_id' => $plan->id,
            ]);

            
            
            
        }
    }
}
