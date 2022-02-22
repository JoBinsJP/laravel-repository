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

    public function getBuilder(): Builder
    {
        $this->applyFilters();
        $this->applyScope();

        return $this->model;
    }

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
     * @param bool    $presentable
     *
     * @return Collection|Model|AbstractPaginator|array|bool
     * @throws LaravelRepositoryException
     */
    protected function makeQueryBuilder(
        Closure $callable,
        bool $presentable = true
    ): Collection|Model|AbstractPaginator|array|bool {
        $this->applyFilters();
        $this->applyScope();

        $result = $callable();

        $this->resetModel();
        $this->resetScope();

        if ( $presentable ) {
            return $this->present($result);
        }

        return $result;
    }
}
