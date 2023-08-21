<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CollabAnswer extends Model
{
    use HasFactory;

    protected $table = 'collab_answers';

    protected $fillable = ['question_id', 'answer', 'user_id', 'guest_name'];

    public function question(): BelongsTo
    {
        return $this->belongsTo(CollabQuestion::class);
    }
}
