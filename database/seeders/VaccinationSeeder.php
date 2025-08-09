<?php

namespace Database\Seeders;

use App\Enums\VaccineEnum;
use App\Enums\ChildAgeEnum;
use App\Enums\VaccineVisitEnum;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class VaccinationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $visits   = VaccineVisitEnum::cases();
        $ages     = ChildAgeEnum::cases();
        $vaccines = VaccineEnum::cases();

        for ($i = 0; $i < 8; $i++) {
            DB::table('vaccinations')->insert([
                'vaccine_visit' => $visits[$i]->value,
                'child_age'     => $ages[$i]->value,
                'vaccine'       => $vaccines[$i]->value,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }
    }
}
