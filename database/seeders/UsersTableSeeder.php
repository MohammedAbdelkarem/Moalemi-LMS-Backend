<?php

namespace Database\Seeders;

use App\Models\Administration\Profile\AdminProfile;
use App\Models\User;
use App\Models\Users\Product\Product;
use App\Models\Users\Profile\Address;
use App\Models\Users\Profile\LoginHistory;
use App\Models\Users\Profile\UserProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        DB::table('users')->delete();

        DB::table('users')->insert(array(
            0 =>
            array(
                'id' => 1,
                'role_id' => 1,
                'name' => 'Template Super Admin',
                'phone_number' => '+963900000000',
                'email' => 'email@email.com',
                'birth_date' => null,
                'city_id' => 1,
                'is_male' => true,
                'language' => 'en',
                'avatar' => NULL,
                'active_notifications' => 1,
                'deactive_at' => NULL,
                'account_verified_at' => '2025-01-12 10:09:07',
                'deleted_at' => NULL,
                'created_at' => '2025-01-12 10:09:07',
                'updated_at' => '2025-01-12 10:09:07',
            ),
            1 =>
            array(
                'id' => 2,
                'role_id' => 3,
                'name' => 'Omar Mansour',
                'phone_number' => '+963900000001',
                'birth_date' => null,
                'email' => null,
                'city_id' => NULL,
                'is_male' => true,
                'language' => 'ar',
                'avatar' => NULL,
                'active_notifications' => 1,
                'deactive_at' => NULL,
                'account_verified_at' => '2025-01-16 23:37:50',
                'deleted_at' => NULL,
                'created_at' => '2025-01-16 23:37:46',
                'updated_at' => '2025-01-16 23:37:55',
            ),
            2 =>
            array(
                'id' => 3,
                'role_id' => 3,
                'name' => 'Fadi Zayed',
                'phone_number' => '+963900000002',
                'birth_date' => null,
                'email' => null,
                'city_id' => NULL,
                'is_male' => true,
                'language' => 'ar',
                'avatar' => NULL,
                'active_notifications' => 1,
                'deactive_at' => NULL,
                'account_verified_at' => '2025-01-16 23:38:13',
                'deleted_at' => NULL,
                'created_at' => '2025-01-16 23:38:08',
                'updated_at' => '2025-01-16 23:38:18',
            )
        ));

        LoginHistory::factory()->count(7)->create([
            "user_id" => 1
        ]);

        LoginHistory::factory()->count(3)->create([
            "user_id" => 2
        ]);

        //Admins
        User::factory()->count(10)
            ->has(AdminProfile::factory())
            ->has(LoginHistory::factory()->count(8), 'loginHistory')
            ->create([
                'role_id' => 2
            ]);

        //Normal Users
        User::factory()->count(20)
            ->has(UserProfile::factory(), 'profile')
            ->has(LoginHistory::factory()->count(8), 'loginHistory')
            ->create([
                'role_id' => 3
            ]);
    }
}