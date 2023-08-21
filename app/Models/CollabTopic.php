<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CollabTopic extends Model
{
    use HasFactory;

    const ACCESS_PUBLIC = 'Public';

    const STATUS_OPEN = 'Open';
    const STATUS_CLOSED = 'Closed';

    protected $table = 'collab_topics';

    protected $fillable = ['user_id', 'topic', 'share_code', 'access_type', 'status'];

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(CollabQuestion::class);
    }
}
