<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::create([
            'app_name' => 'Admin Laravel',
            'copyright' => 'Admin Laravel || 2026',
            'login_title' => 'Admin Laravel',
            'description' => 'Sistem dashboard Root Admin berbasis Laravel yang dirancang untuk manajemen data terpusat, kontrol hak akses pengguna, dan konfigurasi sistem aplikasi secara aman, cepat, dan responsif.',
            'keywords' => 'laravel admin panel, root admin laravel, dashboard super admin, manajemen sistem laravel, aplikasi back-office, laravel 11, sistem manajemen pengguna, enterprise admin dashboard',
        ]);
    }
}
