<?php

namespace JoBins\LaravelRepository\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use JoBins\LaravelRepository\Contracts\FilterCriteria;

/**
 * Class Filterable
 *
 * @package JoBins\LaravelRepository\Filters
 */
class Filterable implements FilterCriteria
{
    public function __construct(
        protected array $filters = []
    ) {
    }

    public function apply(Builder $query): Builder
    {
        if ( method_exists($this, 'preHook') ) {
            $query = $this->preHook($query, $this->filters);
        }

        collect($this->filters)->each(function (mixed $value, string $key) use ($query) {
            $key    = Str::of($key)->camel();
            $method = "{$key}Filter";

            if ( method_exists($this, $method) ) {
                $query = $this->{$method}($query, $value ?? null);
            }
        });

        if ( method_exists($this, 'postHook') ) {
            $query = $this->postHook($query, $this->filters);
        }

        return $query;
    }
}
