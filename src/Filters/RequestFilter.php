<?php

namespace ArgentCrusade\CrudKit\Filters;

use ArgentCrusade\Repository\AbstractRepository;
use ArgentCrusade\Repository\Contracts\RepositoryFilterInterface;

class RequestFilter
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var RepositoryFilterInterface
     */
    public $filter;

    /**
     * @var mixed
     */
    public $value;

    /**
     * Make new RequestFilter instance with the given data.
     *
     * @param string                    $name
     * @param RepositoryFilterInterface $filter
     * @param mixed                     $value  = null
     *
     * @return RequestFilter
     */
    public static function make(string $name, RepositoryFilterInterface $filter, $value = null)
    {
        $instance = app(static::class);

        $instance->name = $name;
        $instance->filter = $filter;
        $instance->value = $value;

        return $instance;
    }

    /**
     * Apply current request filter to the given repository.
     *
     * @param AbstractRepository $repository
     *
     * @return AbstractRepository
     */
    public function apply(AbstractRepository $repository)
    {
        return $this->filter->apply($repository, $this->value);
    }
}
