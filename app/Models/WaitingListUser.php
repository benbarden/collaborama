<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class WaitingListUser extends Model
{
    use HasFactory;

    protected $table = 'waiting_list_users';

    protected $fillable = ['given_name', 'email'];

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
