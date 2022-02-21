<?php

namespace JoBins\LaravelRepository;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Support\Collection;
use JoBins\LaravelRepository\Contracts\RepositoryInterface;
use JoBins\LaravelRepository\Traits\EloquentModelTrait;
use JoBins\LaravelRepository\Traits\FilterableTrait;
use JoBins\LaravelRepository\Traits\Presentable;
use JoBins\LaravelRepository\Traits\ScopeTrait;

/**
 * Class LaravelRepository
 *
 * @package JoBins\LaravelRepository
 */
abstract class LaravelRepository implements RepositoryInterface
{
    use FilterableTrait;
    use EloquentModelTrait;
    use ScopeTrait;
    use Presentable;

    /**
     * @throws Exceptions\LaravelRepositoryException
     * @throws BindingResolutionException
     */
    public function __construct(
        protected Application $app
    ) {
        $this->resetFilters();
        $this->makeModel();
    }

    /**
     * @param array $columns
     *
     * @return Collection|array
     * @throws Exceptions\LaravelRepositoryException
     */
    public function all(array $columns = ['*']): Collection|array
    {
        return $this->makeQueryBuilder(fn() => $this->model->get($columns));
    }

    /**
     * @param int|null $limit
     * @param array    $columns
     *
     * @return AbstractPaginator|array
     * @throws Exceptions\LaravelRepositoryException
     */
    public function paginate(?int $limit = null, array $columns = ['*']): AbstractPaginator|array
    {
        $limit = $limit ?: config('repository.pagination.limit');

        return $this->makeQueryBuilder(fn() => $this->model->paginate($limit, $columns));
    }

    /**
     * @param int|string $modelId
     * @param array      $columns
     *
     * @return Model|array
     * @throws Exceptions\LaravelRepositoryException
     * @throws ModelNotFoundException
     */
    public function find(int|string $modelId, array $columns = ['*']): Model|array
    {
        return $this->makeQueryBuilder(fn() => $this->model->findOrFail($modelId, $columns));
    }

    /**
     * @param string $field
     * @param mixed  $value
     * @param array  $columns
     *
     * @return Model|array
     * @throws Exceptions\LaravelRepositoryException
     */
    public function findByField(string $field, mixed $value, array $columns = ['*']): Model|array
    {
        return $this->makeQueryBuilder(fn() => $this->model->where($field, $value)->firstOrFail($columns));
    }

    /**
     * @param string $field
     * @param mixed  $value
     * @param array  $columns
     *
     * @return Collection|array
     * @throws Exceptions\LaravelRepositoryException
     */
    public function getByField(string $field, mixed $value, array $columns = ['*']): Collection|array
    {
        return $this->makeQueryBuilder(fn() => $this->model->where($field, $value)->get($columns));
    }

    /**
     * @param array $conditions
     * @param array $columns
     *
     * @return Collection|array
     * @throws Exceptions\LaravelRepositoryException
     */
    public function getWhere(array $conditions, array $columns = ['*']): Collection|array
    {
        return $this->makeQueryBuilder(fn() => $this->model->where($conditions)->get($columns));
    }

    /**
     * @param string $field
     * @param array  $values
     * @param array  $columns
     *
     * @return Collection|array
     * @throws Exceptions\LaravelRepositoryException
     */
    public function getWhereIn(string $field, array $values, array $columns = ['*']): Collection|array
    {
        return $this->makeQueryBuilder(fn() => $this->model->whereIn($field, $values)->get($columns));
    }

    /**
     * @param string $field
     * @param array  $values
     * @param array  $columns
     *
     * @return Collection|array
     * @throws Exceptions\LaravelRepositoryException
     */
    public function getWhereNotIn(string $field, array $values, array $columns = ['*']): Collection|array
    {
        return $this->makeQueryBuilder(fn() => $this->model->whereNotIn($field, $values)->get($columns));
    }

    /**
     * @param string $field
     * @param array  $values
     * @param array  $columns
     *
     * @return Collection|array
     * @throws Exceptions\LaravelRepositoryException
     */
    public function getWhereBetween(string $field, array $values, array $columns = ['*']): Collection|array
    {
        return $this->makeQueryBuilder(fn() => $this->model->whereBetween($field, $values)->get($columns));
    }

    /**
     * @param array $data
     *
     * @return Model|array
     * @throws Exceptions\LaravelRepositoryException
     */
    public function create(array $data): Model|array
    {
        return $this->makeQueryBuilder(fn() => $this->model->create($data));
    }

    /**
     * @param array      $data
     * @param int|string $modelId
     *
     * @return Model|array
     * @throws Exceptions\LaravelRepositoryException
     */
    public function update(array $data, int|string $modelId): Model|array
    {
        return $this->makeQueryBuilder(function () use ($data, $modelId) {
            $temporarySkipTransformer = $this->skipTransformer;
            $this->skipTransformer(true);

            $model = $this->model->findOrFail($modelId);

            $model->update($data);
            $this->skipTransformer($temporarySkipTransformer);

            return $model;
        });
    }

    /**
     * @param array $queries
     * @param array $values
     *
     * @return Model|array
     * @throws Exceptions\LaravelRepositoryException
     */
    public function updateOrCreate(array $queries, array $values): Model|array
    {
        return $this->makeQueryBuilder(function () use ($queries, $values) {
            $temporarySkipTransformer = $this->skipTransformer;
            $this->skipTransformer(true);

            $model = $this->model->updateOrCreate($queries, $values);

            $this->skipTransformer($temporarySkipTransformer);

            return $model;
        });
    }

    /**
     * @param int|string $modelId
     *
     * @throws Exceptions\LaravelRepositoryException
     */
    public function delete(int|string $modelId): void
    {
        $this->makeQueryBuilder(fn() => $this->model->delete($modelId));
    }

    /**
     * @param array $where
     *
     * @throws Exceptions\LaravelRepositoryException
     */
    public function deleteWhere(array $where): void
    {
        $this->makeQueryBuilder(fn() => $this->model->where($where)->delete());
    }

    public function orderBy(string $column, string $direction = 'asc'): self
    {
        $this->model = $this->model->orderBy($column, $direction);

        return $this;
    }

    public function with(string|array $relations): self
    {
        $this->model = $this->model->with($relations);

        return $this;
    }
}
