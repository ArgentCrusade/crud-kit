<?php

namespace ArgentCrusade\CrudKit\Requesters;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class DatatableRequester extends ApiRequester
{
    /**
     * Get the requester type.
     *
     * @return string
     */
    public function type()
    {
        return 'datatable';
    }

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
        return array_merge(
            $request->only($keys),
            ['search' => $request->input('search.value', '') ?? '']
        );
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
        $start = intval($request->input('start'));
        $perPage = $this->perPage($request);

        if (!$perPage) {
            return 1;
        }

        return intval($start / $perPage) + 1;
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
        return intval($request->input('length'));
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
        return $this->columnName(
            $request,
            Arr::get($this->orderParameter($request), 'column', '')
        );
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
        $direction = Str::lower(
            Arr::get($this->orderParameter($request), 'dir', '')
        );

        return $direction === 'desc' ? 'desc' : 'asc';
    }

    /**
     * Get the column name at given index.
     *
     * @param Request          $request
     * @param string|int|mixed $index
     * @param mixed            $default = null
     *
     * @return string|mixed
     */
    protected function columnName(Request $request, $index, $default = null)
    {
        return Arr::get(
            $request->input('columns'),
            $index.'.name',
            $default
        );
    }

    /**
     * Get the orderable field.
     *
     * @param Request $request
     *
     * @return array
     */
    protected function orderParameter(Request $request)
    {
        return Arr::first($request->input('order'));
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
        return ['datatable_actions'];
    }
}
