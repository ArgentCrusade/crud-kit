<?php

namespace ArgentCrusade\CrudKit\Tests\Fakes;

use ArgentCrusade\CrudKit\Contracts\CrudRequestInterface;

class FakeRequest implements CrudRequestInterface
{
    /**
     * @var array
     */
    protected $data;

    /**
     * FakeRequest constructor.
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    /**
     * Get the request data.
     *
     * @return array
     */
    public function data()
    {
        return $this->data;
    }
}
