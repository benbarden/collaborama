<?php

namespace App\Domain\WaitingListUser;

use App\Models\WaitingListUser;
class Repo
{
    public function find($id)
    {
        return WaitingListUser::find($id);
    }

    public function emailExists($email)
    {
        $exists = WaitingListUser::where('email', $email)->get();
        return count($exists) > 0;
    }

    public function getByEmail($email)
    {
        return WaitingListUser::where('email', $email)->first();
    }

    public function getAll()
    {
        return WaitingListUser::orderBy('id', 'desc')->get();
    }
}
