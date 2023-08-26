<?php

namespace App\Domain\User;

use App\Models\User;
class Repo
{
    public function getAll()
    {
        return User::orderBy('id', 'desc')->get();
    }
}
