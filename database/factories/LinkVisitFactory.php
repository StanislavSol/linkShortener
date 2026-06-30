<?php

namespace Database\Factories;

use App\Models\Link;
use Illuminate\Database\Eloquent\Factories\Factory;

class LinkVisitFactory extends Factory
{
    public function definition(): array
    {
        return [
            'link_id' => Link::factory(),
            'ip_address' => $this->faker->ipv4(),
            'visited_at' => $this->faker->dateTimeBetween('-30 days', 'now'),
        ];
    }
}
