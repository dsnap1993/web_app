<?php

namespace App\Http\Controllers;

class ActionIndex extends Controllers
{
    public function __invoke(Request $request)
    {
        return redirect()->view('auth.login');
    }
}