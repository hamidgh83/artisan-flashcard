<?php

namespace App\Services;

use App\Models\FlashCard;
use App\Models\User;
use App\Repositories\FlashCardRepository;
use Illuminate\Database\Eloquent\Collection;

class PracticeService
{
    protected FlashCardRepository $repository;

    public function __construct(FlashCardRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get a user practices.
     */
    public function getPractices(User $user): Collection
    {
        return $this->repository->findUserPractices($user);
    }

    /**
     * Flattern a collection of FlashCard model.
     */
    public function flattenPracticeCollection(Collection $collection)
    {
        $items = [];
        foreach ($collection as $model) {
            $card           = $model->only(['question']);
            $card['status'] = 'NOT ANSWERED';
            foreach ($model->practices as $item) {
                $card['status'] = $item->pivot->result ? 'CORRECT' : 'INCORRECT';
            }

            $items[] = $card;
        }

        return $items;
    }

    /**
     * Calculate a user practice completion percentage.
     */
    public function completionPercentage(User $user): int
    {
        if (!$totalQuestions = $user->flashcards->count()) {
            return 0;
        }

        $correctAnswers = $this->repository->countByCorrectAnswers($user);

        return ($correctAnswers / $totalQuestions) * 100;
    }

    /**
     * Add a user practice.
     */
    public function addPractice(User $user, FlashCard $card, ?string $answer = null)
    {
        $user->practices()->attach($card->id, ['result' => $card->answer == $answer]);
    }
}
