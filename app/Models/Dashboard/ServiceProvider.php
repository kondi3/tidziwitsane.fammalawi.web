<?php

namespace App\Models\Dashboard;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceProvider extends Model
{
    use HasFactory;

    const ACTION_MAP = 'geo';
    const ACTION_PHONE = 'phone';
    const ACTION_SMS = 'sms';
    const ACTION_EMAIL = 'email';
    const ACTION_WEB = 'web';

    protected $fillable = [
        'user_id',
        'provider',
        'action',
        'args'
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
