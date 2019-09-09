<?php

namespace ArgentCrusade\CrudKit\CrudOperations;

use ArgentCrusade\CrudKit\Contracts\CrudRequestInterface;
use ArgentCrusade\CrudKit\CrudResource;
use ArgentCrusade\Repository\AbstractRepository;
use Illuminate\Support\Facades\DB;

abstract class AbstractCrudOperation
{
    /** @var bool */
    protected $withDbTransactions = true;

    /**
     * Get the operation's event name.
     *
     * @return string
     */
    abstract public function event();

    /**
     * Execute operation.
     *
     * @param CrudRequestInterface $request
     * @param CrudResource         $resource
     * @param AbstractRepository   $repository
     *
     * @return mixed
     */
    abstract protected function run(CrudRequestInterface $request, CrudResource $resource, AbstractRepository $repository);

    /**
     * Execute CRUD operation.
     *
     * @param CrudRequestInterface $request
     * @param CrudResource         $resource
     * @param AbstractRepository   $repository
     * @param callable|null        $callback
     *
     * @return mixed
     */
    public function execute(CrudRequestInterface $request, CrudResource $resource, AbstractRepository $repository, callable $callback = null)
    {
        if ($this->withDbTransactions) {
            DB::beginTransaction();
        }

        try {
            $result = $this->run($request, $resource, $repository);

            if (method_exists($resource, $this->event())) {
                $result = call_user_func_array([$resource, $this->event()], [$result, $repository, $request]);
            }

            if (is_callable($callback)) {
                $result = call_user_func_array($callback, [$result, $repository, $request]);
            }
        } catch (\Throwable $e) {
            if ($this->withDbTransactions) {
                DB::rollback();
            }

            throw $e;
        }

        if ($this->withDbTransactions) {
            DB::commit();
        }

        return $result;
    }

    /**
     * Disable database transactions & run given callback.
     *
     * @param callable $callback
     *
     * @return mixed
     */
    public function withoutTransactions(callable $callback)
    {
        $this->withDbTransactions = false;

        $result = call_user_func_array($callback, [$this]);

        $this->withDbTransactions = true;

        return $result;
    }
}
