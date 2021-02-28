<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Shashank Jha',
            'mobile' => 9807060707,
            'username' => 'shashank03',
            'password' => 'shashank@123',
            'blood_group' => 'A+',
            'status' => 'approved',
            'role_id' => 1,
        ]);
    }
}
