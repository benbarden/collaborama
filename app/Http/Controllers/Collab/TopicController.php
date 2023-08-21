<?php

namespace App\Http\Controllers\Collab;

use App\Domain\CollabTopic\ShareCode;
use App\Domain\WaitingListUser\Repo as RepoWaitingListUser;
use App\Http\Controllers\Controller;
use App\Models\CollabTopic;
use App\Models\User;
use App\Models\WaitingListUser;
use App\Providers\RouteServiceProvider;
use App\Rules\IsAllowedToRegister;
use App\Rules\IsOnWaitingList;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class TopicController extends Controller
{
    public function __construct(
        protected RepoWaitingListUser $repoWaitingListUser
    ){

    }
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('collab.topic.create');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'topic' => ['required', 'string', 'max:100'],
        ]);
        $validator->validate();

        $shareCode = new ShareCode();
        $code = $shareCode->generateCode();

        $topic = CollabTopic::create([
            'user_id' => $request->user()->id,
            'topic' => $request->topic,
            'share_code' => $code,
            'access_type' => CollabTopic::ACCESS_PUBLIC,
            'status' => CollabTopic::STATUS_OPEN,
        ]);

        $topic->save();

        return redirect(route('user.dashboard'));
    }
}
