<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if ($this->command->confirm('Want to refresh database?', true)) {
            $this->command->call('migrate:refresh');
            $this->command->info('Database is seeded');
        }

        Cache::tags(['product'])->flush();
        Cache::tags(['user'])->flush();

        $this->call([
            UserSeeder::class,
            ProductSeeder::class,
            TagSeeder::class,
            ProductTagSeeder::class
        ]);
    }
}
