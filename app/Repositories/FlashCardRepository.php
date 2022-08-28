<?php

namespace App\Repositories;

use App\Models\FlashCard;
use App\Models\User;
use App\Repositories\Eloquent\BaseRepository;
use Illuminate\Database\Eloquent\Collection;

class FlashCardRepository extends BaseRepository
{
    /**
     * Get all the practices by the user.
     */
    public function findUserPractices(User $user): Collection
    {
        return $this->model->with(['practices' => function ($q) use ($user) {
            $q->where('user_id', '=', $user->id);
        }])->where('user_id', '=', $user->id)->get();
    }

    /**
     * Count all the correctly answered questions by the user.
     */
    public function countByCorrectAnswers(User $user): int
    {
        return $this->model->withWhereHas('practices', function ($q) use ($user) {
            $q->where('user_id', '=', $user->id)->where('result', '=', true);
        })->where('user_id', '=', $user->id)->count();
    }

    /**
     * Count all the answered questions (correct or incorrect) by the user.
     */
    public function countByAnswered(User $user)
    {
        return $this->model->withWhereHas('practices', function ($q) use ($user) {
            $q->where('user_id', '=', $user->id);
        })->where('user_id', '=', $user->id)->count();
    }

    protected function getModelName()
    {
        return FlashCard::class;
    }
}
