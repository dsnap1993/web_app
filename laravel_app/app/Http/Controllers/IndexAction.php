<?php

namespace App\Http\Controllers;

class IndexAction extends Controller
{
    public function __invoke()
    {
        return redirect('/login');
    }
}