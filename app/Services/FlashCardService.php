<?php

namespace App\Services;

use App\Models\FlashCard;
use App\Repositories\FlashCardRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

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
     * Get all flash cards.
     */
    public function getAll(): Collection
    {
        return $this->repository->findAll(['question', 'answer']);
    }
}
