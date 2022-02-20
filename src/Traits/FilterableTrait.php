<?php

namespace JoBins\LaravelRepository\Traits;

use Illuminate\Support\Collection;
use JoBins\LaravelRepository\Contracts\FilterCriteria;

/**
 * Trait CriteriaTrait
 *
 * @package JoBins\LaravelRepository\Traits
 */
trait FilterableTrait
{
    protected bool $skipFilters = false;

    /**
     * @var Collection<FilterCriteria>
     */
    protected Collection $filters;

    public function skipFilters(bool $skip = true): self
    {
        $this->skipFilters = $skip;

        return $this;
    }

    public function resetFilters(): self
    {
        $this->filters = new Collection();

        return $this;
    }

    public function filter(FilterCriteria $filter): self
    {
        $this->filters->push($filter);

        return $this;
    }

    public function removeFilter(string $filter): self
    {
        $this->filters->reject(fn(FilterCriteria $filter) => get_class($filter) === $filter);

        return $this;
    }

    /**
     * @return Collection<FilterCriteria>
     */
    public function getFilters(): Collection
    {
        return $this->filters;
    }

    protected function applyFilters(): self
    {
        if ( $this->skipFilters === true ) {
            return $this;
        }

        $filters = $this->getFilters();

        if ( $filters->isNotEmpty() ) {
            $filters->each(fn(FilterCriteria $filter) => $this->model = $filter->apply($this->model));
        }

        return $this;
    }
}
