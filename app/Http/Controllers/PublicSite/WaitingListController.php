<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WaitingListController extends Controller
{
    public function signup()
    {
        $bindings = [];

        $bindings['AppEnv'] = env('APP_ENV');

        return view('public-site.join-waiting-list', $bindings);
    }
}
