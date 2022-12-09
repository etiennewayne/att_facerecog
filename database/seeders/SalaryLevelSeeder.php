<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SalaryLevelSeeder extends Seeder
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
                'salary_level' => 'LEVEL 1',
                'salary' => 250,
            ],
            [
                'salary_level' => 'LEVEL 2',
                'salary' => 300,
            ],
            [
                'salary_level' => 'LEVEL 3',
                'salary' => 380,
            ],

        ];

        \App\Models\SalaryLevel::insertOrIgnore($data);

        

    }
}
