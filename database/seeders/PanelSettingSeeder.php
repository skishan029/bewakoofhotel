<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PanelSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \Illuminate\Support\Facades\DB::table('panelsettings')->insert([
            'notification_email'    => NULL,
            'email'                 => NULL,
            'contact_one'           => NULL,
            'contact_two'           => NULL,
            'banner_heading'        => NULL,
            'address'               => NULL,
            'facebook'              => NULL,
            'youtube'               => NULL,
            'instagram'             => NULL,
            'twitter'               => NULL,
            'established_year'      => NULL,
            'about_content'         => NULL,
            'owner_name'            => NULL,
            'owner_about'           => NULL,
            'company_logo'          => NULL,
            'favicon_logo'          => NULL,
            'created_at'            => now(),
            'updated_at'            => now(),
        ]);
    }
}
