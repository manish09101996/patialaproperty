<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Patiala Property Admin',
                'email' => 'admin@patialaproperty.com',
                'mobile' => '9999999999',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'is_active' => true,
                'email_verified_at' => now(),
                'mobile_verified_at' => now(),
            ],
            [
                'name' => 'Ramesh Kumar (Owner)',
                'email' => 'owner@patialaproperty.com',
                'mobile' => '8888888888',
                'password' => Hash::make('owner123'),
                'role' => 'owner',
                'is_active' => true,
                'email_verified_at' => now(),
                'mobile_verified_at' => now(),
            ],
            [
                'name' => 'Sukhwinder Singh (Agent)',
                'email' => 'agent@patialaproperty.com',
                'mobile' => '7777777777',
                'password' => Hash::make('agent123'),
                'role' => 'agent',
                'is_active' => true,
                'email_verified_at' => now(),
                'mobile_verified_at' => now(),
            ],
            [
                'name' => 'Gaurav Sharma (User/Buyer)',
                'email' => 'tenant@patialaproperty.com',
                'mobile' => '6666666666',
                'password' => Hash::make('tenant123'),
                'role' => 'user',
                'is_active' => true,
                'email_verified_at' => now(),
                'mobile_verified_at' => now(),
            ],
        ];

        foreach ($users as $u) {
            User::updateOrCreate(
                ['email' => $u['email']],
                [
                    'name' => $u['name'],
                    'mobile' => $u['mobile'],
                    'password' => $u['password'],
                    'role' => $u['role'],
                    'is_active' => $u['is_active'],
                    'email_verified_at' => $u['email_verified_at'],
                    'mobile_verified_at' => $u['mobile_verified_at'],
                ]
            );
        }
    }
}
