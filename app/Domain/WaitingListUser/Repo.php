<?php

namespace App\Domain\WaitingListUser;

use App\Models\WaitingListUser;
class Repo
{
    public function emailExists($email)
    {
        $exists = WaitingListUser::where('email', $email)->get();
        return count($exists) > 0;
    }
}
