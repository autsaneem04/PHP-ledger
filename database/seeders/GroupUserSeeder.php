<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GroupUser;

class GroupUserSeeder extends Seeder
{
    public function run(): void
    {

        GroupUser::create([
            'group_user_name' => 'Admin',
            'is_super_user' => 1
        ]);

        GroupUser::create([
            'group_user_name' => 'User',
            'is_super_user' => 0
        ]);
    }
}
