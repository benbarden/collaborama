<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CollabQuestion extends Model
{
    use HasFactory;

    const ANSWER_TEXT = 1;

    protected $table = 'collab_questions';

    protected $fillable = ['topic_id', 'question', 'question_no', 'answer_type'];

    public function topic(): BelongsTo
    {
        return $this->belongsTo(CollabTopic::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(CollabAnswer::class, 'question_id', 'id');
    }
}
