<?php

namespace Database\Seeders;

use App\Models\Link;
use App\Models\LinkVisit;
use Illuminate\Database\Seeder;

class LinkVisitSeeder extends Seeder
{
    public function run(): void
    {
        Link::all()->each(function ($link) {
            LinkVisit::factory(rand(0, 30))->create([
                'link_id' => $link->id,
            ]);
        });
    }
}
