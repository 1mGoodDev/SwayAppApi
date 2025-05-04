<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Alice Johnson',
                'email' => 'alice@user.com',
                'job' => 'Programmer',
                'password' => Hash::make('password'), // Jangan lupa hashing!
                'status' => 'active',
            ],
            [
                'name' => 'Bob Smith',
                'email' => 'bob@user.com',
                'job' => 'Web Developer',
                'password' => Hash::make('password'),
                'status' => 'inactive',
            ],
            [
                'name' => 'Charlie Brown',
                'email' => 'charlie@user.com',
                'job' => 'Engineer',
                'password' => Hash::make('password'),
                'status' => 'active',
            ],
            [
                'name' => 'Diana Prince',
                'email' => 'diana@user.com',
                'job' => 'UI Designer',
                'password' => Hash::make('password'),
                'status' => 'inactive',
            ],
            [
                'name' => 'Eve Adams',
                'email' => 'eve@user.com',
                'job' => 'Product Designer',
                'password' => Hash::make('password'),
                'status' => 'active',
            ],
        ];

        foreach ($users as $userData) {
            User::create($userData);
        }
    }
}
