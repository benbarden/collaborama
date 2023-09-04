<?php

namespace App\Http\Controllers\Collab;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

use App\Http\Controllers\Controller;
use App\Models\CollabTopic;
use App\Domain\CollabTopic\ShareCode;
use App\Domain\WaitingListUser\Repo as RepoWaitingListUser;
use App\Domain\CollabTopic\Repo as RepoCollabTopic;
use App\Domain\CollabAnswer\Repo as RepoCollabAnswer;

class TopicController extends Controller
{
    public function __construct(
        protected RepoWaitingListUser $repoWaitingListUser,
        protected RepoCollabTopic $repoCollabTopic,
        protected RepoCollabAnswer $repoCollabAnswer
    ){

    }
    /**
     * Display the registration view.
     */
    public function create(Request $request)
    {
        $bindings['TopTitle'] = "Create topic";

        $topicLimit = CollabTopic::BETA_TOPIC_LIMIT;
        $userTopicCount = count($this->repoCollabTopic->getByUser($request->user()->id));
        if ($userTopicCount >= $topicLimit) {
            return redirect(route('user.dashboard'));
        }

        return view('collab.topic.create', $bindings);
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

        return redirect(route('collab.topic.manage', ['topic' => $topic, 'create-success' => 1]));
    }

    public function view(Request $request, $shareCode): View
    {
        $topic = $this->repoCollabTopic->getByShareCode($shareCode);
        if (!$topic) abort(404);

        $bindings = [];
        $bindings['TopTitle'] = "View topic: ".$topic->topic;
        $bindings['Topic'] = $topic;

        $userTopicAnswers = $this->repoCollabAnswer->getByTopicAndUser($topic->id, $request->user()->id);
        $userTopicAnswersKeys = [];
        if ($userTopicAnswers) {
            foreach ($userTopicAnswers as $answer) {
                $userTopicAnswersKeys[$answer->question_id] = json_decode(json_encode($answer), true);
            }
        }
        $bindings['UserTopicAnswers'] = $userTopicAnswersKeys;

        if ($request->get('register-success') == 1) {
            $bindings['RegisterSuccess'] = 1;
        }

        return view('collab.topic.view', $bindings);
    }

    public function manage(Request $request, CollabTopic $topic): View
    {
        if ($topic->user->id != $request->user()->id) abort(403);

        $bindings = [];
        $bindings['TopTitle'] = "Manage topic";
        $bindings['Topic'] = $topic;

        if ($request->get('create-success') == 1) {
            $bindings['CreateSuccess'] = 1;
        }

        return view('collab.topic.manage', $bindings);
    }

    public function delete(Request $request, CollabTopic $topic)
    {
        if ($topic->user->id != $request->user()->id) abort(403);

        if ($topic->questions()->count() > 0) abort(400);

        $topic->delete();

        return 'Deleted';
    }

    public function close(Request $request, CollabTopic $topic)
    {
        if ($topic->user->id != $request->user()->id) abort(403);

        $topic->setStatusClosed();
        $topic->save();

        return '<small>Closed</small>';
    }

    public function reopen(Request $request, CollabTopic $topic)
    {
        if ($topic->user->id != $request->user()->id) abort(403);

        $topic->setStatusOpen();
        $topic->save();

        return '<small>Reopened</small>';
    }
}
