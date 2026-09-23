<?php

namespace Checkout\Identities\Entities;

use Checkout\Common\AbstractQueryFilter;

class AttemptsQueryFilter extends AbstractQueryFilter
{
    /**
     * The number of attempts to skip.
     * [Optional]
     * Default: 0
     * @var int|null $skip
     */
    public $skip;

    /**
     * The maximum number of attempts to return.
     * [Optional]
     * Default: 10
     * @var int|null $limit
     */
    public $limit;
}
