<?php

namespace App\Models\Dashboard;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FAQ extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'question',
        'answer'
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
