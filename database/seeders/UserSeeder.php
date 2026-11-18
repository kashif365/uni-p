<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $this->command?->info('Create your own administrator with php artisan demo:admin.');
    }
}
