<?php

namespace App\Http\Controllers\Staff;

use App\Mail\WaitingListInvite;
use App\Models\WaitingListUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
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

        $bindings['TopTitle'] = "Staff dashboard";
        $bindings['WaitingListUsers'] = $this->repoWaitingListUser->getAll();
        $bindings['UserList'] = $this->repoUser->getAll();

        return view('staff.dashboard', $bindings);
    }

    public function inviteFromWaitingList($wlUserId)
    {
        $wlUser = $this->repoWaitingListUser->find($wlUserId);
        if (!$wlUser) {
            return redirect(route('staff.dashboard'));
        }

        if ($wlUser->allow_register == 1) {
            return redirect(route('staff.dashboard'));
        }

        $wlUser->allow_register = 1;
        $wlUser->save();

        // Send email
        Mail::to($wlUser->email)->send(new WaitingListInvite($wlUser));

        return redirect(route('staff.dashboard'));
    }

}
