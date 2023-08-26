<?php

namespace App\Http\Controllers\Staff;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Http\Controllers\Controller;
use App\Domain\WaitingListUser\Repo as RepoWaitingListUser;
use App\Domain\User\Repo as RepoUser;

class DashboardController extends Controller
{
    public function __construct(
        protected RepoWaitingListUser $repoWaitingListUser,
        protected RepoUser $repoUser
    ){

    }

    public function show(Request $request): View
    {
        $bindings = [];

        $bindings['WaitingListUsers'] = $this->repoWaitingListUser->getAll();
        $bindings['UserList'] = $this->repoUser->getAll();

        return view('staff.dashboard', $bindings);
    }

}
