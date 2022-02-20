<?php

namespace JoBins\LaravelRepository\Traits;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
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
     * @throws BindingResolutionException
     * @throws LaravelRepositoryException
     */
    protected function makeModel(): void
    {
        $model = $this->app->make($this->model());

        if ( !$model instanceof Model ) {
            throw new LaravelRepositoryException(
                "Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model"
            );
        }

        $this->model = $model->newQuery();
    }

    /**
     * @return void
     * @throws BindingResolutionException
     * @throws LaravelRepositoryException
     */
    protected function resetModel(): void
    {
        $this->makeModel();
    }
}
