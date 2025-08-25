<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CustomVerifyEmailController extends VerifyEmailController
{
    public function __invoke(Request $request): RedirectResponse
    {
        // Call parent to perform verification and dispatch Verified event
        $response = parent::__invoke($request);

        // Force redirect to home with verified flag
        return redirect()->to(route('home', absolute: false).'?verified=1');
    }
}
