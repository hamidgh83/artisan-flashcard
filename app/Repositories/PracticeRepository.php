<?php

namespace App\Repositories;

use App\Models\Practice;
use App\Repositories\Eloquent\BaseRepository;

class PracticeRepository extends BaseRepository
{
    protected function getModelName()
    {
        return Practice::class;
    }
}
