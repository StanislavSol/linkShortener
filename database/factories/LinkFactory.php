<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class LinkFactory extends Factory
{
    public function definition(): array
    {
        return [
            'original_url' => $this->faker->url(),
            'short_code' => Str::random(6),
            'user_id' => User::factory(),
        ];
    }
}
