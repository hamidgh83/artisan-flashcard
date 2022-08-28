<?php

namespace App\Repositories;

use App\Models\FlashCard;
use App\Models\User;
use App\Repositories\Eloquent\BaseRepository;
use Illuminate\Database\Eloquent\Collection;

class FlashCardRepository extends BaseRepository
{
    public function findUserPractices(User $user): Collection
    {
        return $this->model->with(['practices' => function ($q) use ($user) {
            $q->where('user_id', '=', $user->id);
        }])->where('user_id', '=', $user->id)->get();
    }

    public function countByCorrectAnswers(User $user): int
    {
        return $this->model->withWhereHas('practices', function ($q) use ($user) {
            $q->where('user_id', '=', $user->id)->where('result', '=', true);
        })->where('user_id', '=', $user->id)->count();
    }

    protected function getModelName()
    {
        return FlashCard::class;
    }
}
