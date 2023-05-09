<?php

namespace App\Models\Dashboard;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Myth extends Model
{
    use HasFactory;

    protected $fillable = ['uuid', 'user_id', 'myth', 'fact'];

    public function getRouteKey()
    {
        return $this->uuid;
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
