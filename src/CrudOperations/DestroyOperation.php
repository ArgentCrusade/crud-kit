<?php

namespace ArgentCrusade\CrudKit\CrudOperations;

use ArgentCrusade\CrudKit\Contracts\CrudRequestInterface;
use ArgentCrusade\CrudKit\CrudResource;
use ArgentCrusade\Repository\AbstractRepository;
use Illuminate\Database\Eloquent\Model;

class DestroyOperation extends AbstractCrudOperation
{
    /** @var Model */
    protected $model;

    /**
     * DeleteOperation constructor.
     *
     * @param Model $model
     */
    public function __construct($model)
    {
        $this->model = $model;
    }

    /**
     * Get the operation's event name.
     *
     * @return string
     */
    public function event()
    {
        return 'destroyed';
    }

    /**
     * Execute operation.
     *
     * @param CrudRequestInterface $request
     * @param CrudResource         $resource
     * @param AbstractRepository   $repository
     *
     * @return mixed
     */
    protected function run(CrudRequestInterface $request, CrudResource $resource, AbstractRepository $repository)
    {
        return $repository->delete($this->model->getKey());
    }
}
