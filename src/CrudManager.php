<?php

namespace ArgentCrusade\CrudKit;

use ArgentCrusade\CrudKit\Contracts\CrudRequestInterface;
use ArgentCrusade\CrudKit\Contracts\ResourceRequesterInterface;
use ArgentCrusade\CrudKit\CrudOperations\CreateOperation;
use ArgentCrusade\CrudKit\CrudOperations\DestroyOperation;
use ArgentCrusade\CrudKit\CrudOperations\UpdateOperation;
use ArgentCrusade\CrudKit\Filters\RequestFilter;
use ArgentCrusade\Repository\AbstractRepository;
use ArgentCrusade\Repository\Contracts\RepositoryOrderingInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class CrudManager
{
    /**
     * @var CrudResource
     */
    protected $resource;

    /**
     * @var AbstractRepository
     */
    protected $repository;

    /**
     * @var ResourceRequesterInterface
     */
    protected $requester;

    /**
     * CrudManager constructor.
     *
     * @param CrudResource               $resource
     * @param ResourceRequesterInterface $requester
     */
    public function __construct(CrudResource $resource, ResourceRequesterInterface $requester)
    {
        $this->resource = $resource;
        $this->repository = $resource->repository();
        $this->requester = $requester;
    }

    /**
     * Get the resource.
     *
     * @return CrudResource
     */
    public function resource()
    {
        return $this->resource;
    }

    /**
     * Get the resource repository.
     *
     * @return AbstractRepository
     */
    public function repository()
    {
        return $this->repository;
    }

    /**
     * Get the requester.
     *
     * @return ResourceRequesterInterface
     */
    public function requester()
    {
        return $this->requester;
    }

    /**
     * List items and return filtered repository without loading results.
     *
     * @param Request  $request
     * @param callable $callback = null
     *
     * @return AbstractRepository
     */
    public function list(Request $request, callable $callback = null)
    {
        $filters = $this->requester()->filters($this->resource(), $request);
        $repository = $this->repositoryWithFilters($filters);

        if (is_callable($callback)) {
            call_user_func_array($callback, [$repository, $request]);
        }

        return $this->applyOrdering($request, $repository);
    }

    /**
     * Get relationships list.
     *
     * @param Request $request
     *
     * @return array
     */
    public function getIncludes(Request $request)
    {
        $list = $this->resource()->availableIncludes();
        $requestIncludes = $this->requester()->includes($request);

        if (!count($list) || !count($requestIncludes)) {
            return [];
        }

        $relationships = [];

        foreach ($requestIncludes as $include) {
            if (empty($list[$include])) {
                continue;
            }

            $relationships = array_merge($relationships, Arr::wrap($list[$include]));
        }

        return array_unique($relationships);
    }

    /**
     * Parse includes relationships.
     *
     * @param Request $request
     *
     * @return array
     *
     * @deprecated Use CrudManager::getIncludes() instead.
     * @see CrudManager::getIncludes()
     */
    public function parseInlcudesRelationships(Request $request)
    {
        return $this->getIncludes($request);
    }

    /**
     * Apply filters to the repository.
     *
     * @param array $filters
     *
     * @return AbstractRepository
     */
    protected function repositoryWithFilters(array $filters)
    {
        $repository = $this->repository();

        collect($filters)->each(function (RequestFilter $filter) use ($repository) {
            $filter->apply($repository);
        });

        return $repository;
    }

    /**
     * Apply ordering to the repository (if required).
     *
     * @param Request            $request
     * @param AbstractRepository $repository
     *
     * @return AbstractRepository
     */
    protected function applyOrdering(Request $request, AbstractRepository $repository)
    {
        $orderColumn = $this->requester()->orderBy($request);
        $orderDirection = $this->requester()->orderDirection($request);

        if (!$orderColumn || !$orderDirection) {
            return $repository;
        }

        // First of all, we need to check if repository has "complex"
        // ordering that was registered to the current order column.
        // If true, this instance will take care of data ordering.

        if (!is_null($ordering = Arr::get($repository->ordering(), $orderColumn))) {
            /** @var RepositoryOrderingInterface $ordering */
            $ordering = is_string($ordering) ? app($ordering) : $ordering;

            return $ordering->apply($repository, $orderColumn, $orderDirection);
        }

        // Otherwise we want to check if the current order column
        // presented in the repository's orderable columns list.
        // If so, simply apply it to the safe ordering method.

        if (!in_array($orderColumn, $repository->orderableColumns())) {
            return $repository;
        }

        return $repository->safeOrderBy($orderColumn, $orderDirection);
    }

    /**
     * Find resource by primary key.
     *
     * @param mixed $id
     *
     * @return Model|null
     */
    public function find($id)
    {
        try {
            return $this->repository()->findOrFail($id);
        } catch (\Throwable $e) {
            // Do nothing.
        }
    }

    /**
     * Create new resource.
     *
     * @param CrudRequestInterface $request
     * @param callable             $callback = null
     *
     * @return Model|null
     */
    public function create(CrudRequestInterface $request, callable $callback = null)
    {
        return (new CreateOperation())->execute($request, $this->resource(), $this->repository(), $callback);
    }

    /**
     * Update given resource.
     *
     * @param CrudRequestInterface $request
     * @param Model                $resource
     * @param callable             $callback = null
     *
     * @return Model|null
     */
    public function update(CrudRequestInterface $request, $resource, callable $callback = null)
    {
        return (new UpdateOperation($resource))->execute($request, $this->resource(), $this->repository(), $callback);
    }

    /**
     * Destroy given resource.
     *
     * @param CrudRequestInterface $request
     * @param Model                $resource
     * @param callable             $callback = null
     *
     * @return mixed
     */
    public function destroy(CrudRequestInterface $request, $resource, callable $callback = null)
    {
        return (new DestroyOperation($resource))->execute($request, $this->resource(), $this->repository(), $callback);
    }
}
