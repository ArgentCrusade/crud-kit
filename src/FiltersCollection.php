<?php

namespace ArgentCrusade\CrudKit;

class FiltersCollection
{
    /**
     * Available filters list.
     *
     * @var array
     */
    protected $filters = [];

    /**
     * Register given filters list for the given requester type.
     *
     * @param string|array $requesterType
     * @param array        $filters
     *
     * @return $this
     */
    public function register($requesterType, array $filters)
    {
        foreach (array_wrap($requesterType) as $type) {
            $this->filters[$type] = $filters;
        }

        return $this;
    }

    /**
     * Determines whether the collection has filters for the given requester type.
     *
     * @param string $requesterType
     *
     * @return bool
     */
    public function has(string $requesterType)
    {
        return !empty($this->filters[$requesterType]);
    }

    /**
     * Get the filters list for the given requester type.
     *
     * @param string $requesterType
     *
     * @return array
     */
    public function get(string $requesterType)
    {
        return $this->filters[$requesterType] ?? [];
    }
}
