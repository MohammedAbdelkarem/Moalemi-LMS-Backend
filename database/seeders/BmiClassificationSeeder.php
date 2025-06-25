<?php

namespace Database\Seeders;

use App\Models\BmiClassification;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BmiClassificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BmiClassification::insert([
            [
                'classification' => 'نحافة شديدة', // Severe Thinness (<16)
                'start'          => 0,
                'end'            => 16,
            ],
            [
                'classification' => 'نحافة معتدلة', // Moderate Thinness (16-17)
                'start'          => 16,
                'end'            => 17,
            ],
            [
                'classification' => 'نحافة خفيفة', // Mild Thinness (17-18.5)
                'start'          => 17,
                'end'            => 18.5,
            ],
            [
                'classification' => 'طبيعي', // Normal (18.5-25)
                'start'          => 18.5,
                'end'            => 25,
            ],
            [
                'classification' => 'زيادة الوزن', // Overweight (25-30)
                'start'          => 25,
                'end'            => 30,
            ],
            [
                'classification' => 'السمنة من الدرجة الأولى', // Overweight I / Obesity Class I (30-35)
                'start'          => 30,
                'end'            => 35,
            ],
            [
                'classification' => 'السمنة من الدرجة الثانية', // Overweight II / Obesity Class II (35-40)
                'start'          => 35,
                'end'            => 40,
            ],
            [
                'classification' => 'السمنة من الدرجة الثالثة', // Overweight III / Obesity Class III (>40)
                'start'          => 40,
                'end'            => null,
            ],
        ]);

    }
}
