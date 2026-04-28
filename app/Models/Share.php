<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Share extends Model
{
    protected $fillable = [
        'user_id',
        'forum_id',
        'platform_id',
        'platform_name',
        'url',
        'ip_address',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that shared the content.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the forum post that was shared.
     */
    public function forum(): BelongsTo
    {
        return $this->belongsTo(Forum::class);
    }
    
    /**
     * Get the forum post that was shared.
     */
    public function platform(): BelongsTo
    {
        return $this->belongsTo(Platform::class);
    }
}
