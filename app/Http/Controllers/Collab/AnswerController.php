<?php

namespace App\Http\Controllers\Collab;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

use App\Http\Controllers\Controller;
use App\Models\CollabTopic;
use App\Models\CollabQuestion;
use App\Models\CollabAnswer;
use App\Domain\CollabTopic\Repo as RepoCollabTopic;
use App\Domain\CollabAnswer\Repo as RepoCollabAnswer;

class AnswerController extends Controller
{
    public function __construct(
        protected RepoCollabTopic $repoCollabTopic,
        protected RepoCollabAnswer $repoCollabAnswer
    ){

    }
    /**
     * Display the registration view.
     */
    public function create(Request $request, $shareCode, CollabQuestion $question): View
    {
        $topic = $this->repoCollabTopic->getByShareCode($shareCode);
        if (!$topic) abort(404);

        $existingAnswer = $this->repoCollabAnswer->getByQuestionAndUser($question->id, $request->user()->id);

        $bindings = [];
        $bindings['Topic'] = $topic;
        $bindings['Question'] = $question;
        $bindings['AuthUser'] = $request->user();
        if ($existingAnswer) {
            $bindings['Answer'] = $existingAnswer->answer;
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

        $answer = $this->repoCollabAnswer->getByQuestionAndUser($question->id, $request->user()->id);
        if ($answer) {

            $answer->answer = $request->answer;

        } else {

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
        }

        $answer->save();

        return redirect(route('collab.topic.view', ['shareCode' => $topic->share_code]));
    }

}
