<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

use App\Models\WaitingListUser;
use App\Domain\WaitingListUser\Repo as RepoWaitingListUser;

class WaitingListController extends Controller
{
    public function __construct(
        protected RepoWaitingListUser $repoWaitingListUser
    ){

    }

    public function signup(Request $request)
    {
        $waitingListGivenName = $request->post('waiting_list_given_name');
        $waitingListEmail = $request->post('waiting_list_email');

        if (!$waitingListGivenName || !$waitingListEmail) {
            return redirect()->route('welcome');
        }

        $bindings = [];
        $bindings['AppEnv'] = env('APP_ENV');

        if ($this->repoWaitingListUser->emailExists($waitingListEmail)) {

            $bindings['ErrorMsg'] = 'Your email is already on our waiting list.';

        } else {

            $wlUser = new WaitingListUser([
                'given_name' => $waitingListGivenName,
                'email' => $waitingListEmail,
            ]);
            $wlUser->save();

            $bindings['ListPlacement'] = $wlUser->id;

        }




        return view('public-site.join-waiting-list', $bindings);
    }
}
