<?php

namespace JoBins\LaravelRepository\Contracts;

use Illuminate\Database\Eloquent\Model;

/**
 * Interface Filterable
 *
 * @package JoBins\LaravelRepository\Contracts
 */
interface Filterable
{
    public function apply(Model $model, RepositoryInterface $repository);
}
