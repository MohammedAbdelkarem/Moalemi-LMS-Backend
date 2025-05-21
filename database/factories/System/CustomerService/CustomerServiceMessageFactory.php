<?php

namespace Database\Factories\System\CustomerService;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\System\Info\FAQ>
 */
class CustomerServiceMessageFactory extends Factory
{
    public function definition(): array
    {
        $arFaker = \Faker\Factory::create('ar_SA');
        $data = [
            // "card_id" => 2,
            "message" => $arFaker->sentence(random_int(2, 20)),
            "user_id" => random_int(1, 3)
        ];
        return $data;
    }
}
