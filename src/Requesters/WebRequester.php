<?php

namespace ArgentCrusade\CrudKit\Requesters;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WebRequester extends AbstractRequester
{
    /**
     * Get the filter values from the given request.
     *
     * @param Request $request
     * @param array   $keys
     *
     * @return array
     */
    protected function filterValues(Request $request, array $keys)
    {
        return $request->only($keys);
    }

    /**
     * Get the requester type.
     *
     * @return string
     */
    public function type()
    {
        return 'web';
    }

    /**
     * Get the request's page number.
     *
     * @param Request $request
     *
     * @return int
     */
    public function page(Request $request)
    {
        return intval($request->input('page', 1));
    }

    /**
     * Get the request's items pagination limit.
     *
     * @param Request $request
     *
     * @return int
     */
    public function perPage(Request $request)
    {
        return intval($request->input('count', 20));
    }

    /**
     * Get the request's ordering field.
     *
     * @param Request $request
     *
     * @return string
     */
    public function orderBy(Request $request)
    {
        $field = $request->input('order_by');

        return Str::startsWith($field, '-') ? Str::substr($field, 1) : $field;
    }

    /**
     * Get the request's order direction.
     *
     * @param Request $request
     *
     * @return string
     */
    public function orderDirection(Request $request)
    {
        $field = $request->input('order_by');

        return Str::startsWith($field, '-') ? 'desc' : 'asc';
    }
}
