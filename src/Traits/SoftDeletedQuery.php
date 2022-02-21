<?php

namespace JoBins\LaravelRepository\Traits;

/**
 * Trait SoftDeletedQuery
 *
 * @package JoBins\LaravelRepository\Traits
 */
trait SoftDeletedQuery
{
    public function onlyTrashed(): self
    {
        $this->scopeQuery(function ($query) {
            return $query->onlyTrashed();
        });

        return $this;
    }

    public function withTrashed(): self
    {
        $this->scopeQuery(function ($query) {
            return $query->withTrashed();
        });

        return $this;
    }
}
