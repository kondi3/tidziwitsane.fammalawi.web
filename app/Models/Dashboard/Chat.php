<?php

namespace App\Models\Dashboard;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chat extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function messages() : HasMany
    {
        return $this->hasMany(Message::class);
    }
}
