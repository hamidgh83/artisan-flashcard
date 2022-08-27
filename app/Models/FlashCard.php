<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlashCard extends Model
{
    use HasFactory;

    public const UPDATED_AT = null;

    protected $fillable = ['user_id', 'question', 'answer'];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
    ];
}
