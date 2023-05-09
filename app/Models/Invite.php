<?php

namespace App\Models;

use App\Events\UserInvitedEvent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;

class Invite extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = ['uuid', 'user_id', 'user_type', 'email'];

    protected $dispatchesEvents = [
        'created' => UserInvitedEvent::class,
    ];

    public function getRouteKey() : Mixed
    {
        return $this->uuid;
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
