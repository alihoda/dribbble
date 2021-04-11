<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $userCount = max((int)$this->command->ask('How many users would you like?', 10), 1);

        User::factory()->userAdmin()->create();
        User::factory($userCount)->create();
    }
}
