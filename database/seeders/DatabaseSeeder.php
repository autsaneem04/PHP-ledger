<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\GroupUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(GroupUserSeeder::class);

        $adminGroup = GroupUser::where('group_user_name', 'Admin')->first();
        $userGroup = GroupUser::where('group_user_name', 'User')->first();

        // Create Admin User
        User::create([
            'name' => 'Admin User',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'group_user_id' => $adminGroup ? $adminGroup->group_user_id : null,
            'is_enable' => true,
        ]);

        // Create General User
        User::create([
            'name' => 'General User',
            'username' => 'user',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'group_user_id' => $userGroup ? $userGroup->group_user_id : null,
            'is_enable' => true,
        ]);
    }
}
