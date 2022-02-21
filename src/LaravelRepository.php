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
     * @param int|string $id
     * @param array      $columns
     *
     * @return Model|array
     * @throws Exceptions\LaravelRepositoryException
     * @throws ModelNotFoundException
     */
    public function find(int|string $id, array $columns = ['*']): Model|array
    {
        return $this->makeQueryBuilder(fn() => $this->model->findOrFail($id, $columns));
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
}
