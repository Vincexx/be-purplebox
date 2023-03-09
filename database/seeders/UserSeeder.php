<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            "first_name" => "Admin",
            "middle_name" => "Mid",
            "last_name" => "Super",
            "address" => "Tanza, Cavite",
            "role" => "Admin",
            "contact_num" => "09756348605",
            "email" => "admin@gmail.com",
            "password" => Hash::make("password"),
        ]);
    }
}
