<?php

namespace Database\Seeders;

use App\Models\Plan;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Database\Seeder;
use App\Enums\PublishStatusEnum;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MainSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $catagory = Category::create([
            'name' => 'catagory_1',
            'bio' => 'bio of cat 1'
        ]);

        for($i=1 ; $i<3 ; $i++)
        {
            SubCategory::create([
                'name' => 'sub_category_' . $i,
                'bio' => "bio of sub cat" . $i,
                'category_id' => 1
            ]);

            Plan::create([
                'title' => 'plan_' . $i,
                'price' => $i * 1000,
                'number_of_days' => $i * 10,
                'discount_percentage' => '3',
                'discount_start_at' => '2025-04-04',
                'discount_end_at' => '2025-07-07',
                'publish_status' => PublishStatusEnum::PUBLISHED
            ]);
        }
    }
}
