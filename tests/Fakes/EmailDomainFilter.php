<?php

namespace ArgentCrusade\CrudKit\Tests\Fakes;

use ArgentCrusade\Repository\AbstractRepository;
use ArgentCrusade\Repository\Contracts\RepositoryFilterInterface;

class EmailDomainFilter implements RepositoryFilterInterface
{
    public function apply(AbstractRepository $repository, $value)
    {
        if (!$value) {
            return $repository;
        }

        return $repository->pushCriteria(new EmailDomainCriteria($value));
    }
}
