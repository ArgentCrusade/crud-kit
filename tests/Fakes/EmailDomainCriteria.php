<?php

namespace ArgentCrusade\CrudKit\Tests\Fakes;

use ArgentCrusade\Repository\Contracts\Criterias\CacheableCriteriaInterface;
use Illuminate\Database\Eloquent\Builder;

class EmailDomainCriteria implements CacheableCriteriaInterface
{
    /** @var string */
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
     * Get cache hash for the current criteria.
     *
     * @return array
     */
    public function getCacheHash(): string
    {
        return 'domain-'.$this->domain;
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
