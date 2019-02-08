<?php

namespace ArgentCrusade\CrudKit\Requesters;

use ArgentCrusade\CrudKit\Contracts\ResourceRequesterInterface;
use ArgentCrusade\CrudKit\CrudResource;
use ArgentCrusade\CrudKit\Filters\RequestFilter;
use ArgentCrusade\Repository\Contracts\RepositoryFilterInterface;
use Illuminate\Http\Request;

abstract class AbstractRequester implements ResourceRequesterInterface
{
    /**
     * Get the filter values from the given request.
     *
     * @param Request $request
     * @param array   $keys
     *
     * @return array
     */
    abstract protected function filterValues(Request $request, array $keys);

    /**
     * Get the resource filters that applies to the current requester.
     *
     * @param CrudResource $resource
     * @param Request      $request
     *
     * @return RequestFilter[]|array
     */
    public function filters(CrudResource $resource, Request $request)
    {
        $filters = $resource->filters()->get($this->type());
        $values = collect(
            $this->filterValues($request, array_keys($filters))
        );

        if (!count($filters) || !count($values)) {
            return [];
        }

        return collect($filters)
            ->map(function (RepositoryFilterInterface $filter, string $name) use ($values) {
                if (!$values->has($name)) {
                    return null;
                }

                return RequestFilter::make($name, $filter, $values->get($name));
            })
            ->reject(null)
            ->toArray();
    }

    /**
     * Get the transformer includes list.
     *
     * @param Request $request
     *
     * @return array
     */
    public function includes(Request $request)
    {
        return [];
    }
}
