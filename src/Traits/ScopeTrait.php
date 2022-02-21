<?php

namespace JoBins\LaravelRepository\Traits;

use Closure;

/**
 * Trait ScopeTrait
 *
 * @package JoBins\LaravelRepository\Traits
 */
trait ScopeTrait
{
    protected Closure|null $scopeQuery = null;

    public function scopeQuery(Closure $scopeQuery): self
    {
        $this->scopeQuery = $scopeQuery;

        return $this;
    }

    protected function resetScope(): self
    {
        $this->scopeQuery = null;

        return $this;
    }

    protected function applyScope(): self
    {
        if ( $this->scopeQuery && is_callable($this->scopeQuery) ) {
            $this->model = $this->scopeQuery->call($this->model);
        }

        return $this;
    }
}
