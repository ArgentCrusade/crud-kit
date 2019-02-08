<?php

namespace ArgentCrusade\CrudKit\Requesters;

use Illuminate\Http\Request;

class ApiRequester extends WebRequester
{
    /**
     * Get the requester type.
     *
     * @return string
     */
    public function type()
    {
        return 'api';
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
        if (!$request->filled('with')) {
            return [];
        }

        $with = $request->input('with', []);

        if (is_array($with)) {
            return $with;
        }

        return collect(explode(',', $with))
            ->map(function ($include) {
                return trim($include ?? '');
            })
            ->reject('')
            ->unique()
            ->toArray();
    }
}
