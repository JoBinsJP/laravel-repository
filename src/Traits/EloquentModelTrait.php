<?php

namespace JoBins\LaravelRepository\Traits;

use Closure;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Support\Collection;
use JoBins\LaravelRepository\Exceptions\LaravelRepositoryException;

/**
 * Trait EloquentModelTrait
 *
 * @package JoBins\LaravelRepository\Traits
 */
trait EloquentModelTrait
{
    protected Builder $model;

    abstract public function model(): string;

    /**
     * @throws LaravelRepositoryException
     */
    protected function makeModel(): void
    {
        try {
            $model = $this->app->make($this->model());
        } catch (BindingResolutionException $exception) {
            throw new LaravelRepositoryException(
                sprintf('Unable to resolve model: %s. Error: %s', $this->model(), $exception->getMessage())
            );
        }

        if ( !$model instanceof Model ) {
            throw new LaravelRepositoryException(
                "Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model"
            );
        }

        $this->model = $model->newQuery();
    }

    /**
     * @return void
     * @throws LaravelRepositoryException
     */
    protected function resetModel(): void
    {
        $this->makeModel();
    }

    /**
     * @param Closure $callable
     *
     * @return Collection|Model|AbstractPaginator|array
     * @throws LaravelRepositoryException
     */
    protected function makeQueryBuilder(Closure $callable): Collection|Model|AbstractPaginator|array
    {
        $this->applyFilters();
        $this->applyScope();

        $result = $callable();

        $this->resetModel();
        $this->resetScope();

        return $this->present($result);
    }
}
