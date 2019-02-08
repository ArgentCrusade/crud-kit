<?php

namespace ArgentCrusade\CrudKit;

use ArgentCrusade\Repository\AbstractRepository;

abstract class CrudResource
{
    /**
     * Get the resource repository.
     *
     * @return AbstractRepository
     */
    abstract public function repository();

    /**
     * Get the resource forms.
     *
     * @return FormsCollection
     */
    public function forms()
    {
        return new FormsCollection();
    }

    /**
     * Get the resource filters.
     *
     * @return FiltersCollection
     */
    public function filters()
    {
        return new FiltersCollection();
    }

    /**
     * Get the available includes list & their relationships.
     *
     * @return array
     */
    public function availableIncludes()
    {
        return [];
    }

    /**
     * Get the available includes list & their relationships.
     *
     * @return array
     *
     * @deprecated Use CrudResource::includes() instead.
     * @see CrudResource::availableIncludes()
     */
    public function includesRelationships()
    {
        return $this->availableIncludes();
    }
}
