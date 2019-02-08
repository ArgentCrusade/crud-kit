<?php

namespace ArgentCrusade\CrudKit\Contracts;

use ArgentCrusade\CrudKit\CrudResource;
use ArgentCrusade\CrudKit\Filters\RequestFilter;
use Illuminate\Http\Request;

interface ResourceRequesterInterface
{
    /**
     * Get the requester type.
     *
     * @return string
     */
    public function type();

    /**
     * Get the resource filters that applies to the current requester.
     *
     * @param CrudResource $resource
     * @param Request      $request
     *
     * @return RequestFilter[]
     */
    public function filters(CrudResource $resource, Request $request);

    /**
     * Get the request's page number.
     *
     * @param Request $request
     *
     * @return int
     */
    public function page(Request $request);

    /**
     * Get the request's items pagination limit.
     *
     * @param Request $request
     *
     * @return int
     */
    public function perPage(Request $request);

    /**
     * Get the request's ordering field.
     *
     * @param Request $request
     *
     * @return string
     */
    public function orderBy(Request $request);

    /**
     * Get the request's order direction.
     *
     * @param Request $request
     *
     * @return string
     */
    public function orderDirection(Request $request);

    /**
     * Get the transformer includes list.
     *
     * @param Request $request
     *
     * @return array
     */
    public function includes(Request $request);
}
