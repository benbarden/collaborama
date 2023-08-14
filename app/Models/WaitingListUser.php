<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaitingListUser extends Model
{
    use HasFactory;

    protected $table = 'waiting_list_users';

    protected $fillable = ['given_name', 'email'];

}
