<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function show()
    {
        $bindings = [];

        return view('public-site.welcome', $bindings);
    }
}
