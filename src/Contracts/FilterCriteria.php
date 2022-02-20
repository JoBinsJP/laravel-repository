<?php

namespace JoBins\LaravelRepository\Contracts;

use Illuminate\Database\Eloquent\Builder;

/**
 * Interface FilterCriteria
 *
 * @package JoBins\LaravelRepository\Contracts
 */
interface FilterCriteria
{
    public function apply(Builder $query): Builder;
}
