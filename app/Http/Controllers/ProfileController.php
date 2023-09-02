<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\CollabTopic;
use App\Http\Requests\ProfileUpdateRequest;
use App\Domain\CollabTopic\Repo as RepoCollabTopic;
use App\Domain\CollabAnswer\Repo as RepoCollabAnswer;

class ProfileController extends Controller
{
    public function __construct(
        protected RepoCollabTopic $repoCollabTopic,
        protected RepoCollabAnswer $repoCollabAnswer
    ){

    }

    public function dashboard(Request $request): View
    {
        $bindings = [];

        $bindings['TopTitle'] = "Dashboard";
        $bindings['user'] = $request->user();
        $bindings['TopicLimit'] = CollabTopic::BETA_TOPIC_LIMIT;

        $myTopics = $this->repoCollabTopic->getByUser($request->user()->id);
        if ($myTopics) {
            $myTopicsForView = [];
            foreach ($myTopics as $topic) {
                $topicAnswers = $this->repoCollabAnswer->getByTopic($topic->id);
                $topic->answers = $topicAnswers;
                $myTopicsForView[] = $topic;
            }
            $bindings['MyTopics'] = $myTopicsForView;
        }

        return view('user.dashboard', $bindings);
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
