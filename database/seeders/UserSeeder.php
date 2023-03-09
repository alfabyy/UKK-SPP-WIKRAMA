<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
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
        $user = 
        [
            [
                'name' => 'super admin',
                'email' => 'admin',
                'password' => Hash::make('12345678'),
                'level' => 'admin',
            ],
            [
                'name' => 'petugas user',
                'email' => 'petugas',
                'password' => Hash::make('12345678'),
                'level' => 'petugas'

            ]
        ];

        foreach ($user as $key => $value) {
            User::create($value);
        }
    }
}
