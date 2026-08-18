<?php

namespace Database\Seeders;

use App\Models\StableBranding;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Seed the admin user and default stable branding.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@stables.test'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        StableBranding::updateOrCreate(
            ['id' => 1],
            [
                'name' => 'My Stables',
                'logo_path' => null,
            ]
        );
    }
}
