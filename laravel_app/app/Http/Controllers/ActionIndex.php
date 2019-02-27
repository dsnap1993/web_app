<?php

namespace App\Http\Controllers;

class ActionIndex extends Controller
{
    public function __invoke(Request $request)
    {
        return redirect()->view('auth.login');
    }
}