<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CollabAnswer extends Model
{
    use HasFactory;

    protected $table = 'collab_answers';

    protected $fillable = ['question_id', 'answer', 'user_id', 'guest_name', 'needs_discussion', 'dont_know'];

    public function question(): BelongsTo
    {
        return $this->belongsTo(CollabQuestion::class);
    }
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
