<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CitiesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('cities')->delete();

        \DB::table('cities')->insert(array(
            0 =>
            array(
                'id' => 1,
                'name_en' => 'Damascus',
                'name_ar' => 'دمشق',
                'created_at' => '2024-02-26 06:07:46',
                'updated_at' => '2024-02-26 06:07:46',
            ),
            1 =>
            array(
                'id' => 2,
                'name_en' => 'Rif Dimashq',
                'name_ar' => 'ريف دمشق',
                'created_at' => '2024-02-26 06:07:46',
                'updated_at' => '2024-02-26 06:07:46',
            ),
            2 =>
            array(
                'id' => 3,
                'name_en' => 'Aleppo',
                'name_ar' => 'حلب',
                'created_at' => '2024-02-26 06:09:02',
                'updated_at' => '2024-02-26 06:09:02',
            ),
            3 =>
            array(
                'id' => 4,
                'name_en' => 'Homs',
                'name_ar' => 'حمص',
                'created_at' => '2024-02-26 06:09:02',
                'updated_at' => '2024-02-26 06:09:02',
            ),
            4 =>
            array(
                'id' => 5,
                'name_en' => 'Hama',
                'name_ar' => 'حماة',
                'created_at' => '2024-02-26 06:09:02',
                'updated_at' => '2024-02-26 06:09:02',
            ),
            5 =>
            array(
                'id' => 6,
                'name_en' => 'Raqqa',
                'name_ar' => 'الرقة',
                'created_at' => '2024-02-26 06:09:02',
                'updated_at' => '2024-02-26 06:09:02',
            ),
            6 =>
            array(
                'id' => 7,
                'name_en' => 'Deir ez-Zor',
                'name_ar' => 'دير الزور',
                'created_at' => '2024-02-26 06:09:02',
                'updated_at' => '2024-02-26 06:09:02',
            ),
            7 =>
            array(
                'id' => 8,
                'name_en' => 'Quneitra',
                'name_ar' => 'القنيطرة',
                'created_at' => '2024-02-26 06:09:02',
                'updated_at' => '2024-02-26 06:09:02',
            ),
            8 =>
            array(
                'id' => 9,
                'name_en' => 'Tartus',
                'name_ar' => 'طرطوس',
                'created_at' => '2024-02-26 06:09:02',
                'updated_at' => '2024-02-26 06:09:02',
            ),
            9 =>
            array(
                'id' => 10,
                'name_en' => 'Daraa',
                'name_ar' => 'درعا',
                'created_at' => '2024-02-26 06:09:02',
                'updated_at' => '2024-02-26 06:09:02',
            ),
            10 =>
            array(
                'id' => 11,
                'name_en' => 'As-Suwayda',
                'name_ar' => 'السويداء',
                'created_at' => '2024-02-26 06:13:37',
                'updated_at' => '2024-02-26 06:13:37',
            ),
            11 =>
            array(
                'id' => 12,
                'name_en' => 'Idlib',
                'name_ar' => 'إدلب',
                'created_at' => '2024-02-26 06:13:37',
                'updated_at' => '2024-02-26 06:13:37',
            ),
            12 =>
            array(
                'id' => 13,
                'name_en' => 'Al-Hasakah',
                'name_ar' => 'الحسكة',
                'created_at' => '2024-02-26 06:14:16',
                'updated_at' => '2024-02-26 06:14:16',
            ),
            13 =>
            array(
                'id' => 14,
                'name_en' => 'Latakia',
                'name_ar' => 'اللاذقية',
                'created_at' => '2024-02-26 06:14:16',
                'updated_at' => '2024-02-26 06:14:16',
            ),
        ));
    }
}
