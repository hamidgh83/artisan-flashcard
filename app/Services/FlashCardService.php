<?php

namespace App\Services;

use App\Models\FlashCard;
use App\Models\User;
use App\Repositories\FlashCardRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;

class FlashCardService
{
    protected Collection $collection;

    protected FlashCardRepository $repository;

    public function __construct(Collection $collection, FlashCardRepository $repository)
    {
        $this->collection = $collection;
        $this->repository = $repository;
    }

    /**
     * Add FlashCard model to collection.
     */
    public function add(FlashCard $model): Collection
    {
        return $this->collection->add($model);
    }

    /**
     * Insert a collection of items into the database and return status.
     */
    public function store(Collection $items): bool
    {
        if ($items->count() > 0) {
            $items->each(function ($model) {
                $model->created_at = Carbon::now();
            });

            return $this->repository->insert($this->collection->toArray());
        }

        return false;
    }

    /**
     * Get all flash cards filtered by columns.
     */
    public function getAll(User $user, ?array $columns = null): SupportCollection
    {
        return $user->flashcards->map(function ($model) use ($columns) {
            if (!empty($columns)) {
                return $model->only($columns);
            }

            return $model;
        });
    }
}
