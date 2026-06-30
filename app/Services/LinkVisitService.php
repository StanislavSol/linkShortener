<?php

namespace App\Services;

use App\Models\Link;
use App\Models\LinkVisit;
use Illuminate\Http\Request;

class LinkVisitService
{
    public function recordVisit(Link $link, Request $request): LinkVisit
    {
        return LinkVisit::create([
            'link_id' => $link->id,
            'ip_address' => $request->ip(),
            'visited_at' => now(),
        ]);
    }
}
