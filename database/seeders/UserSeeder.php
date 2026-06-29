<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = new \App\Models\User();
        $user->name =  env('APP_NAME');
        $user->email = "superadmin@gmail.com";
        $user->password = \Illuminate\Support\Facades\Hash::make('Admin@123#');
        $user->email_verified_at = now();
        $user->user_type = '1';
        $user->save();

        $user = new \App\Models\User();
        $user->name =  'Employee';
        $user->email = "employee@gmail.com";
        $user->password = \Illuminate\Support\Facades\Hash::make('Admin@123#');
        $user->email_verified_at = now();
        $user->user_type = '2';
        $user->save();
    }
}
