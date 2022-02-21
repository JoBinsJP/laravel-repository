<?php

namespace JoBins\LaravelRepository\Traits;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use JoBins\LaravelRepository\Serializer\DataArraySerializer;
use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection as ResourceCollection;
use League\Fractal\Resource\Item as ResourceItem;
use League\Fractal\Resource\ResourceInterface;
use League\Fractal\TransformerAbstract;

/**
 * Trait Presentable
 *
 * @package JoBins\LaravelRepository\Traits
 */
trait Presentable
{
    protected ?TransformerAbstract $transformer     = null;
    protected Manager              $fractal;
    protected array                $includes        = [];
    protected bool                 $skipTransformer = false;

    public function setTransformer(TransformerAbstract $transformer): self
    {
        $this->transformer = $transformer;

        return $this;
    }

    public function resetTransformer(): self
    {
        $this->transformer = null;

        return $this;
    }

    public function setIncludes(array|string $includes): self
    {
        if ( is_array($includes) ) {
            $this->includes = [...$this->includes, ...$includes];
        }

        if ( is_string($includes) ) {
            $this->includes = [...$this->includes, $includes];
        }

        return $this;
    }

    public function present(
        Collection|AbstractPaginator|Model $data
    ): Collection|AbstractPaginator|Model|array {
        if ( $this->skipTransformer || !$this->transformer ) {
            return $data;
        }

        $this->initializeFractal();
        $resource = $this->getResource($data);

        return $this->fractal->createData($resource)->toArray();
    }

    public function skipTransformer(bool $skip = true): self
    {
        $this->skipTransformer = $skip;

        return $this;
    }

    protected function initializeFractal()
    {
        $this->fractal = new Manager();
        $this->parseIncludes();
        $this->setupSerializer();
    }

    protected function parseIncludes()
    {
        if ( count($this->includes) ) {
            $this->fractal->parseIncludes($this->includes);
        }
    }

    protected function setupSerializer()
    {
        $this->fractal->setSerializer(new DataArraySerializer());
    }

    protected function getResource(Collection|AbstractPaginator|Model $data): ResourceInterface
    {
        if ( $data instanceof Collection ) {
            return $this->transformCollection($data);
        }
        if ( $data instanceof AbstractPaginator ) {
            return $this->transformPaginator($data);
        }

        return $this->transformItem($data);
    }

    protected function transformCollection(Collection $data): ResourceCollection
    {
        return new ResourceCollection($data, $this->transformer);
    }

    protected function transformPaginator(AbstractPaginator|LengthAwarePaginator|Paginator $data): ResourceCollection
    {
        $collection = $data->getCollection();
        $resource   = new ResourceCollection($collection, $this->transformer);
        $resource->setPaginator(new IlluminatePaginatorAdapter($data));

        return $resource;
    }

    protected function transformItem(Model $data): ResourceItem
    {
        return new ResourceItem($data, $this->transformer);
    }
}
