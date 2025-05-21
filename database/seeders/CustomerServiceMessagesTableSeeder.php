<?php

namespace Database\Seeders;

use App\Models\System\CustomerService\CustomerServiceCard;
use App\Models\System\CustomerService\CustomerServiceMessage;
use Illuminate\Database\Seeder;

class CustomerServiceMessagesTableSeeder extends Seeder
{
    public function run(): void
    {
        $cards = CustomerServiceCard::pluck('id')->toArray();
        foreach ($cards as $card) {
            CustomerServiceMessage::factory()->count(54)->create([
                "card_id" => $card,
            ]);
        }
    }
}
