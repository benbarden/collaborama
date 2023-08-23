<?php

namespace App\Http\Controllers\Auth;

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

class RegisteredUserController extends Controller
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
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class,
                new IsOnWaitingList($this->repoWaitingListUser),
                new IsAllowedToRegister($this->repoWaitingListUser)],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        $validator->validate();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $wlUser = $this->repoWaitingListUser->getByEmail($request->email);
        $wlUser->user_id = $user->id;
        $wlUser->save();

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }

    public function registerViaTopic(Request $request, CollabTopic $topic): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        $validator->validate();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $wlUser = $this->repoWaitingListUser->getByEmail($request->email);
        if ($wlUser) {
            $wlUser->user_id = $user->id;
            $wlUser->save();
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('collab.topic.view', ['shareCode' => $topic->share_code]).'?register-success=1');
    }
}
