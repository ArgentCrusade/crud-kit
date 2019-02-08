<?php

namespace ArgentCrusade\CrudKit\Http\Controllers;

use ArgentCrusade\CrudKit\Contracts\ResourceRequesterInterface;
use ArgentCrusade\CrudKit\Requesters\ApiRequester;
use League\Fractal\TransformerAbstract;

abstract class CrudApiController extends CrudController
{
    /**
     * Get the resource transformer.
     *
     * @return TransformerAbstract
     */
    abstract public function transformer();

    /**
     * Get the resource requester.
     *
     * @return ResourceRequesterInterface
     */
    public function requester()
    {
        return new ApiRequester();
    }
}
