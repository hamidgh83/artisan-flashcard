<?php

namespace App\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Model;

/**
 * Interface EloquentRepositoryInterface.
 */
interface EloquentRepositoryInterface
{
    public function create(array $attributes): Model;

    public function delete(Model $model): ?bool;

    public function update(Model $model, array $data, array $fillable = []);

    public function find(int $id): ?Model;

    public function findBy(array $conditions, array $relations = [], bool $paginate = true, int $pageSize = 10);
}
