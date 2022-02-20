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
     * @throws BindingResolutionException
     * @throws Exceptions\LaravelRepositoryException
     */
    public function all(array $columns = ['*']): Collection|array
    {
        $this->applyFilters();
        $this->applyScope();

        $results = $this->model->get($columns);

        $this->resetModel();
        $this->resetScope();

        return $this->present($results);
    }

    /**
     * @param int|null $limit
     * @param array    $columns
     *
     * @return AbstractPaginator|array
     * @throws BindingResolutionException
     * @throws Exceptions\LaravelRepositoryException
     */
    public function paginate(?int $limit = null, array $columns = ['*']): AbstractPaginator|array
    {
        $this->applyFilters();
        $this->applyScope();

        $limit   = $limit ?: config('repository.pagination.limit');
        $results = $this->model->paginate($limit, $columns);

        $this->resetModel();
        $this->resetScope();

        return $this->present($results);
    }

    /**
     * @param int|string $id
     * @param array      $columns
     *
     * @return Model|array
     * @throws BindingResolutionException
     * @throws Exceptions\LaravelRepositoryException
     * @throws ModelNotFoundException
     */
    public function find(int|string $id, array $columns = ['*']): Model|array
    {
        $this->applyFilters();
        $this->applyScope();

        $result = $this->model->findOrFail($id, $columns);

        $this->resetModel();
        $this->resetScope();

        return $this->present($result);
    }
}
