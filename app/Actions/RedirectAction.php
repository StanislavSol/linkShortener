<?php

namespace App\Actions;

use App\Models\Link;
use App\Services\LinkVisitService;
use Illuminate\Http\Request;

class RedirectAction
{
    public function __construct(
        protected LinkVisitService $visitService
    ) {}

    public function execute(string $code, Request $request): string
    {
        $link = Link::where('short_code', $code)->firstOrFail();

        $this->visitService->recordVisit($link, $request);

        return $link->original_url;
    }
}
