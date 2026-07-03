<?php

namespace Database\Seeders;

use App\Models\Contacts;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::factory()->count(10)->create();

        foreach ($users as $user) {
            Contacts::factory()->count(5)->create([
                'user_id' => $user->id
            ]);
        }
    }
}
