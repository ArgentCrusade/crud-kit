<?php

namespace ArgentCrusade\CrudKit\Tests\Fakes;

use ArgentCrusade\Repository\AbstractRepository;

class UsersRepository extends AbstractRepository
{
    protected $orderable = [
        'id', 'name', 'email', 'timezone', 'created_at', 'updated_at',
    ];

    protected $searchable = [
        'name', 'email',
    ];

    public function model()
    {
        return User::class;
    }
}
