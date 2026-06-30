<?php

namespace Database\Seeders;

use App\Models\Link;
use App\Models\User;
use Illuminate\Database\Seeder;

class LinkSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::factory(3)->create();

        $users->each(function ($user) {
            Link::factory(rand(5, 15))->create([
                'user_id' => $user->id,
            ]);
        });
    }
}
