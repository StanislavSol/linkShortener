<?php

namespace App\Http\Controllers;

use App\Actions\RedirectAction;
use Illuminate\Http\Request;

class RedirectController extends Controller
{
    public function __construct(
        protected RedirectAction $redirectAction
    ) {}

    public function redirect($code, Request $request)
    {
        $originalUrl = $this->redirectAction->execute($code, $request);

        return redirect($originalUrl);
    }
}
