<?php

namespace ArgentCrusade\CrudKit\Tests\Fakes;

use ArgentCrusade\Repository\Contracts\Criterias\CriteriaInterface;
use Illuminate\Database\Eloquent\Builder;

class EmailDomainCriteria implements CriteriaInterface
{
    /**
     * @var string
     */
    protected $domain;

    /**
     * Anonymous constructor.
     *
     * @param string $domain
     */
    public function __construct(string $domain)
    {
        $this->domain = $domain;
    }

    /**
     * @param Builder $model
     *
     * @return mixed
     */
    public function apply($model)
    {
        return $model->where('email', 'like', '%@'.$this->domain);
    }
}
