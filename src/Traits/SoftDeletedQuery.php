<?php

namespace JoBins\LaravelRepository\Traits;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Trait SoftDeletedQuery
 *
 * @package JoBins\LaravelRepository\Traits
 */
trait SoftDeletedQuery
{
    public function onlyTrashed(): self
    {
        /** @var SoftDeletes $model */
        $model       = $this->model;
        $this->model = $model::onlyTrashed();

        return $this;
    }

    public function withTrashed(): self
    {
        /** @var SoftDeletes $model */
        $model       = $this->model;
        $this->model = $model::withTrashed();

        return $this;
    }
}
