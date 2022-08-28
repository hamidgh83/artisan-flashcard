<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class FlashCard extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'question', 'answer'];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function practices(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'practices')->withPivot('result')->withTimestamps();
    }
}
