<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
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
        //
        $data = [
            [
                'username' => 'admin',
                'lname' => 'COTALES',
                'fname' => 'CARESS IRAH',
                'mname' => '',
                'sex' => 'FEMALE',
                'province' => 'MISAMIS OCCIDENTAL',
                'city' => 'TANGUB CITY',
                'barangay' => 'CANIANGAN',
                'street' => 'P-6',
                //'email' => 'admin@dev.com',
                'contact_no' => '099124646',
                'role' => 'ADMINISTRATOR',
                'password' => Hash::make('a')
            ],
        ];

        \App\Models\User::insertOrIgnore($data);
    }
}
