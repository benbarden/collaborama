<?php

namespace App\Domain\CollabAnswer;

use App\Models\CollabAnswer;

use Illuminate\Support\Facades\DB;

class Repo
{
    public function getByTopic($topicId)
    {
        return DB::table('collab_answers')
            ->join('collab_questions', 'collab_answers.question_id', '=', 'collab_questions.id')
            ->join('collab_topics', 'collab_questions.topic_id', '=', 'collab_topics.id')
            ->where('collab_topics.id', $topicId)
            ->get();
    }

    public function getByTopicAndUser($topicId, $userId)
    {
        return DB::table('collab_answers')
            ->join('collab_questions', 'collab_answers.question_id', '=', 'collab_questions.id')
            ->join('collab_topics', 'collab_questions.topic_id', '=', 'collab_topics.id')
            ->where('collab_topics.id', $topicId)
            ->where('collab_answers.user_id', $userId)
            ->get();
    }

    public function getByQuestionAndUser($questionId, $userId)
    {
        return CollabAnswer::where('question_id', $questionId)->where('user_id', $userId)->first();
    }
}
