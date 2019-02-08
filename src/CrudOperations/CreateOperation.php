<?php

namespace ArgentCrusade\CrudKit\CrudOperations;

use ArgentCrusade\CrudKit\Contracts\CrudRequestInterface;
use ArgentCrusade\CrudKit\CrudResource;
use ArgentCrusade\Repository\AbstractRepository;

class CreateOperation extends AbstractCrudOperation
{
    /**
     * Get the operation's event name.
     *
     * @return string
     */
    public function event()
    {
        return 'created';
    }

    /**
     * Execute operation.
     *
     * @param CrudRequestInterface $request
     * @param CrudResource $resource
     * @param AbstractRepository $repository
     *
     * @return mixed
     */
    protected function run(CrudRequestInterface $request, CrudResource $resource, AbstractRepository $repository)
    {
        return $repository->create($request->data());
    }
}
