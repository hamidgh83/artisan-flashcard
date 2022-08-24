<?php

namespace App\Repositories\Eloquent;

use App\Exceptions\RepositoryException;
use Carbon\Carbon;
use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository implements EloquentRepositoryInterface
{
    protected $fillable = [];

    /**
     * @var Model
     */
    protected $model;

    /**
     * @var \Illuminate\Database\Eloquent\Builder
     */
    protected $query;

    private $container;

    /**
     * BaseRepository constructor.
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->model     = $this->makeModel();
    }

    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws RepositoryException
     */
    public function query(): Builder
    {
        if ($this->query instanceof Builder) {
            return $this->query;
        }

        return $this->model->newQuery();
    }

    public function pluck($column, $key)
    {
        return $this->query()->pluck($column, $key);
    }

    /**
     * Make model.
     *
     * @throws RepositoryException
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     *
     * @return mixed
     */
    public function makeModel(): Model
    {
        $model = $this->container->make($this->getModelName());

        if (!$model instanceof Model) {
            throw new RepositoryException('Class {' . get_class($this->model) . '} must be an instance of Illuminate\\Database\\Eloquent\\Model');
        }

        return $model;
    }

    public function create(array $attributes): Model
    {
        return $this->model->create($attributes);
    }

    public function updateOrCreate(array $data, array $conditions)
    {
        return $this->model->updateOrCreate($data, $conditions);
    }

    public function insert(array $rows): bool
    {
        return $this->query()->insert($rows);
    }

    public function countBy(array $conditions)
    {
        return $this->query()->where($conditions)->count();
    }

    /**
     * @param $id
     *
     * @return Model
     */
    public function find($id): ?Model
    {
        return $this->model->find($id);
    }

    /**
     * Find all models.
     *
     * @param null|array $columns An array of columns to be apeared in the result
     */
    public function findAll(?array $columns = null): Collection
    {
        return $this->model::all($columns ?? '*');
    }

    /**
     * Find a model with its relations.
     */
    public function findWithRelations(int $id, array $relations): ?Model
    {
        return $this
            ->model
            ->with($relations)
            ->find($id)
        ;
    }

    /**
     * Find a collection of models by conditions.
     *
     * @param array $conditions Array of conditions
     * @param array $relations  An array of relations
     * @param bool  $paginate   Either paginated or not
     * @param int   $pageSize   Page size
     *
     * @return ?Collection|?LengthAwarePaginator A collection or paginated result
     */
    public function findBy(array $conditions, array $relations = [], bool $paginate = true, int $pageSize = 10)
    {
        $query = $this
            ->query()
            ->with($relations)
            ->where($conditions)
        ;

        return $paginate ? $query->paginate($pageSize) : $query->get();
    }

    /**
     * Find a model by conditions.
     *
     * @param array $conditions Array of conditions
     * @param array $relations  An array of relations
     *
     * @return ?Model The first result if the collection
     */
    public function findOneBy(array $conditions, array $relations = [])
    {
        return $this
            ->query()
            ->with($relations)
            ->where($conditions)
            ->first()
        ;
    }

    /**
     * Delete a model.
     *
     * @param Model $object
     */
    public function delete(Model $model): ?bool
    {
        if (is_numeric($model)) {
            $model = $this->find($model)->first();
        }

        return $model->delete();
    }

    /**
     * Destroy an array or collection of ids.
     *
     * @param array|Collection $ids An array or collection of ids
     *
     * @return int Number of destroyed ids
     */
    public function destroy($ids): int
    {
        return $this->model->destroy($ids);
    }

    /**
     * Update an entity.
     *
     * @param mixed $object
     *
     * @return Model model
     */
    public function update(Model $model, array $data, array $fillable = [])
    {
        if (!$model instanceof Model) {
            $model = $this->find($model);
        }

        $data['updated_at'] = new Carbon('now');

        $model = $this->fill($data, $model, $fillable);
        $model->save();

        return $model;
    }

    /**
     * This method will fill the given $object by the given $array.
     * If the $fillable parameter is not available it will use the fillable
     * array of the class.
     *
     * @param Model $object
     *
     * @return mixed
     */
    public function fill(array $data, $object, array $fillable = [])
    {
        if (empty($fillable)) {
            $fillable = $this->model->getFillable();
        }

        if (!empty($fillable)) {
            // Just fill it if fillable array is not empty
            $object->fillable($fillable)->fill($data);
        }

        return $object;
    }

    public function with($relations, $callback = null): Builder
    {
        if (!empty($relations)) {
            $this->query = $this->query()->with($relations, $callback);
        }

        return $this->query();
    }

    abstract protected function getModelName();
}
