<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserCard extends Model
{
    protected $fillable = [
        'user_id',
        'card_id',
        'card_name',
        'set_id',
        'rarity',
        'quantity',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
