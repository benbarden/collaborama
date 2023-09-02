<?php

namespace App\Http\Controllers\Collab;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

use App\Http\Controllers\Controller;
use App\Models\CollabTopic;
use App\Models\CollabQuestion;
use App\Domain\CollabTopic\Repo as RepoCollabTopic;

class QuestionController extends Controller
{
    public function __construct(
        protected RepoCollabTopic $repoCollabTopic
    ){

    }
    /**
     * Display the registration view.
     */
    public function create(Request $request, CollabTopic $topic): View
    {
        $bindings = [];
        $bindings['TopTitle'] = "Add question";
        $bindings['Topic'] = $topic;
        return view('collab.question.create', $bindings);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, CollabTopic $topic): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'question' => ['required', 'string', 'max:100'],
            'question_no' => ['required', 'integer']
        ]);
        $validator->validate();

        $question = CollabQuestion::create([
            'topic_id' => $request->topic->id,
            'question' => $request->question,
            'question_no' => $request->question_no,
            'answer_type' => CollabQuestion::ANSWER_TEXT,
        ]);

        $question->save();

        return redirect(route('collab.topic.manage', ['topic' => $topic]));
    }

}
