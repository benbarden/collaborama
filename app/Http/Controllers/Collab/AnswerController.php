<?php

namespace App\Http\Controllers\Collab;

use App\Models\CollabAnswer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

use App\Http\Controllers\Controller;
use App\Models\CollabTopic;
use App\Models\CollabQuestion;
use App\Domain\CollabTopic\Repo as RepoCollabTopic;

class AnswerController extends Controller
{
    public function __construct(
        protected RepoCollabTopic $repoCollabTopic
    ){

    }
    /**
     * Display the registration view.
     */
    public function create(Request $request, $shareCode, CollabQuestion $question): View
    {
        $topic = $this->repoCollabTopic->getByShareCode($shareCode);
        if (!$topic) abort(404);

        $bindings = [];
        $bindings['Topic'] = $topic;
        $bindings['Question'] = $question;
        if ($request->user()) {
            $bindings['AuthUser'] = $request->user();
        }
        return view('collab.answer.create', $bindings);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, $shareCode, CollabQuestion $question): RedirectResponse
    {
        $topic = $this->repoCollabTopic->getByShareCode($shareCode);
        if (!$topic) abort(404);

        $validationRules = [
            'answer' => ['required', 'string'],
        ];
        if (!$request->user()) {
            $validationRules['guest_name'] = ['required', 'string', 'max:100'];
        }
        $validator = Validator::make($request->all(), $validationRules);
        $validator->validate();

        $values = [
            'question_id' => $question->id,
            'answer' => $request->answer,
        ];
        if ($request->user()) {
            $values['user_id'] = $request->user()->id;
        } else {
            $values['guest_name'] = $request->guest_name;
        }

        $answer = CollabAnswer::create($values);

        $answer->save();

        return redirect(route('collab.topic.view', ['shareCode' => $topic->share_code]));
    }

}
