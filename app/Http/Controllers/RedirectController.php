<?php

namespace App\Http\Controllers;

use App\Models\Link;
use App\Models\LinkVisit;
use Illuminate\Http\Request;

class RedirectController extends Controller
{
    public function redirect($code)
    {
        $link = Link::where('short_code', $code)->firstOrFail();
        $data = [
            "link_id" => $link->id,
            "ip_address" => request()->ip(),
            "visited_at" => now(),
        ];

        $linkVisit = new LinkVisit();
        $linkVisit->fill($data);
        $linkVisit->save();

    return redirect($link->original_url);
    }
}
