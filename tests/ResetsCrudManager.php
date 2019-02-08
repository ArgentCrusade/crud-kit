<?php

namespace ArgentCrusade\CrudKit\Tests;

use ArgentCrusade\CrudKit\CrudManager;
use ArgentCrusade\CrudKit\Requesters\WebRequester;
use ArgentCrusade\CrudKit\Tests\Fakes\UsersResource;

trait ResetsCrudManager
{
    /**
     * @var CrudManager
     */
    protected $manager;

    protected function setUp()
    {
        parent::setUp();

        $this->resetManager();
    }

    public function resetManager()
    {
        $this->manager = new CrudManager(app(UsersResource::class), app(WebRequester::class));

        return $this->manager;
    }
}
