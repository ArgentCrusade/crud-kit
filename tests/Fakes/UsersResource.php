<?php

namespace ArgentCrusade\CrudKit\Tests\Fakes;

use ArgentCrusade\CrudKit\CrudResource;
use ArgentCrusade\CrudKit\FiltersCollection;

class UsersResource extends CrudResource
{
    protected $lastEvent;

    public function repository()
    {
        return app(UsersRepository::class);
    }

    public function filters()
    {
        return (new FiltersCollection())->register('web', [
            'email_domain' => new EmailDomainFilter(),
        ]);
    }

    public function getLastEvent()
    {
        return $this->lastEvent;
    }

    public function created($resource)
    {
        $this->lastEvent = 'created';

        return $resource;
    }

    public function updated($resource)
    {
        $this->lastEvent = 'updated';

        return $resource;
    }

    public function destroyed($resource)
    {
        $this->lastEvent = 'destroyed';

        return $resource;
    }
}
