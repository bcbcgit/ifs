<?php

namespace App\Models\Chat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_code',
        'name',
        'message',
        'status',
        'name_color',
        'log_color',
        'visible',
    ];
}
