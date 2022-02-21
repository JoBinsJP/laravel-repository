<?php

namespace JoBins\LaravelRepository\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Support\Collection;
use JoBins\LaravelRepository\Exceptions\LaravelRepositoryException;
use League\Fractal\TransformerAbstract;

/**
 * Interface RepositoryInterface
 *
 * @package JoBins\LaravelRepository\Contracts
 */
interface RepositoryInterface
{
    /** ********************** Filters ******************************* */
    public function skipFilters(bool $status = true): self;

    public function resetFilters(): self;

    public function filter(FilterCriteria $filter): self;

    public function removeFilter(string $filter): self;

    /**
     * @return Collection<FilterCriteria>
     */
    public function getFilters(): Collection;

    /** ********************** /Filters ****************************** */

    /** ********************** Transformers ************************** */

    public function setTransformer(TransformerAbstract $transformer): self;

    public function resetTransformer(): self;

    public function skipTransformer(bool $skip = true): self;

    public function setIncludes(array|string $includes): self;

    public function present(
        Collection|AbstractPaginator|Model $data
    ): Collection|AbstractPaginator|Model|array;

    /** ********************* /Transformers ************************** */

    /** ******************** Repository Methods ********************** */

    /**
     * @param array $columns
     *
     * @return Collection|array
     * @throws LaravelRepositoryException
     */
    public function all(array $columns = ['*']): Collection|array;

    /**
     * @param int|null $limit
     * @param array    $columns
     *
     * @return AbstractPaginator|array
     * @throws LaravelRepositoryException
     */
    public function paginate(?int $limit = null, array $columns = ['*']): AbstractPaginator|array;

    /**
     * @param int|string $id
     * @param array      $columns
     *
     * @return Model|array
     * @throws LaravelRepositoryException
     * @throws ModelNotFoundException
     */
    public function find(int|string $id, array $columns = ['*']): Model|array;

    /**
     * @param string $field
     * @param mixed  $value
     * @param array  $columns
     *
     * @return Model|array
     * @throws LaravelRepositoryException
     * @throws ModelNotFoundException
     */
    public function findByField(string $field, mixed $value, array $columns = ['*']): Model|array;

    /**
     * @param string $field
     * @param mixed  $value
     * @param array  $columns
     *
     * @return Collection|array
     * @throws LaravelRepositoryException
     */
    public function getByField(string $field, mixed $value, array $columns = ['*']): Collection|array;

    /**
     * @param array $conditions
     * @param array $columns
     *
     * @return Collection|array
     * @throws LaravelRepositoryException
     */
    public function getWhere(array $conditions, array $columns = ['*']): Collection|array;

    /**
     * @param string $field
     * @param array  $values
     * @param array  $columns
     *
     * @return Collection|array
     * @throws LaravelRepositoryException
     */
    public function getWhereIn(string $field, array $values, array $columns = ['*']): Collection|array;

    /**
     * @param string $field
     * @param array  $values
     * @param array  $columns
     *
     * @return Collection|array
     * @throws LaravelRepositoryException
     */
    public function getWhereNotIn(string $field, array $values, array $columns = ['*']): Collection|array;

    /**
     * @param string $field
     * @param array  $values
     * @param array  $columns
     *
     * @return Collection|array
     * @throws LaravelRepositoryException
     */
    public function getWhereBetween(string $field, array $values, array $columns = ['*']): Collection|array;

    /**
     * @param array $data
     *
     * @return Model|array
     * @throws LaravelRepositoryException
     */
    public function create(array $data): Model|array;

    /** ******************* /Repository Methods ********************** */
}
