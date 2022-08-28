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
        $totalQuestions = $user->flashcards->count();
        $correctAnswers = $this->repository->countByCorrectAnswers($user);

        return $this->percentage($correctAnswers, $totalQuestions);
    }

    /**
     * Add a user practice.
     */
    public function addPractice(User $user, FlashCard $card, ?string $answer = null)
    {
        $user->practices()->attach($card->id, ['result' => $card->answer == $answer]);
    }

    /**
     * Reset all practices by the given user.
     */
    public function reset(User $user): int
    {
        return $user->practices()->detach();
    }

    /**
     * Return stats.
     */
    public function getStats(User $user)
    {
        $totalQuestions = $user->flashcards->count();
        $totalAnswers   = $this->repository->countByAnswered($user);
        $correctAnswers = $this->repository->countByCorrectAnswers($user);

        return [
            'totalQuestions' => $totalQuestions,
            'totalAnswers'   => $this->percentage($totalAnswers, $totalQuestions) . '%',
            'correctAnswers' => $this->percentage($correctAnswers, $totalQuestions) . '%',
        ];
    }

    private function percentage(int $val, int $base)
    {
        return $base > 0 ? ($val / $base) * 100 : 0;
    }
}
