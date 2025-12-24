<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@news.local'],
            [
                'name' => 'Admin News',
                'email' => 'admin@cnn.com',
                'password' => Hash::make('Password123!'),
                'role' => 'admin',
                'is_active' => 1,
                'profile_photo_path' => null,
            ]
        );

        $this->command->info('Admin user seeded: admin@cnn.com (Password123!)');
    }
}
