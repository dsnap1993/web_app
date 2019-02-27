<?php

namespace App\Http\Controllers;

class IndexAction extends Controller
{
    public function __invoke(Request $request)
    {
        return redirect()->view('auth.login');
    }
}