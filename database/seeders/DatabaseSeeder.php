<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $dummyUser = [
            [
                'name' => 'Nama User',
                'email' => 'user@test.mail',
                'phone_number' => '085812345678',
                'role' => 2,
                'password' => Hash::make('12345678')
            ],
            [
                'name' => 'Nama Admin',
                'email' => 'admin@test.mail',
                'phone_number' => '085812345678',
                'role' => 1,
                'password' => Hash::make('12345678')
            ],
        ];

        foreach ($dummyUser as $row) {
            User::create([
                'name' => $row['name'],
                'email' => $row['email'],
                'phone_number' => $row['phone_number'],
                'role' => $row['role'],
                'password' => $row['password']
            ]);
        }

        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
