<?php

namespace App\Repositories;

use App\Models\FlashCard;
use App\Repositories\Eloquent\BaseRepository;

class FlashCardRepository extends BaseRepository
{
    protected function getModelName()
    {
        return FlashCard::class;
    }
}
