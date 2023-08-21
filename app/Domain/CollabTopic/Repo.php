<?php

namespace App\Domain\CollabTopic;

use App\Models\CollabTopic;

class Repo
{
    public function getByUser($userId)
    {
        return CollabTopic::where('user_id', $userId)->orderBy('created_at', 'desc')->get();
    }

    public function getByShareCode($shareCode)
    {
        return CollabTopic::where('share_code', $shareCode)->first();
    }
}
