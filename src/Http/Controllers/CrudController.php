<?php

namespace ArgentCrusade\CrudKit\Http\Controllers;

use ArgentCrusade\CrudKit\Contracts\ResourceRequesterInterface;
use ArgentCrusade\CrudKit\CrudManager;
use ArgentCrusade\CrudKit\CrudResource;
use ArgentCrusade\CrudKit\Requesters\WebRequester;

abstract class CrudController
{
    /**
     * Crud manager instance.
     *
     * @var CrudManager
     */
    protected $manager;

    /**
     * Get the resource.
     *
     * @return CrudResource
     */
    abstract public function resource();

    /**
     * Get the resource requester.
     *
     * @return ResourceRequesterInterface
     */
    public function requester()
    {
        return new WebRequester();
    }

    /**
     * Get the CRUD manager.
     *
     * @return CrudManager
     */
    public function crudManager()
    {
        if (!is_null($this->manager)) {
            return $this->manager;
        }

        return $this->manager = new CrudManager($this->resource(), $this->requester());
    }
}
