<?php

namespace Database\Factories\System\Info;

use App\Enums\AppTypes;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\System\Info\FaqCategory>
 */
class FaqCategoryFactory extends Factory
{
    public function definition(): array
    {
        $arFaker = \Faker\Factory::create('ar_SA');
        return [
            'name' => [
                'ar' => $arFaker->unique()->paragraph(1),
                'en' => $this->faker->unique()->sentence(5),
            ],
            'app'       => $this->faker->randomElement(AppTypes::values()),
            "update_by" => $this->faker->randomElement([null, 1]),
        ];
    }
}
