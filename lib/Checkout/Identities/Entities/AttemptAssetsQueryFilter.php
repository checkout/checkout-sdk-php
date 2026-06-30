<?php

namespace Checkout\Identities\Entities;

use Checkout\Common\AbstractQueryFilter;

class AttemptAssetsQueryFilter extends AbstractQueryFilter
{
    /**
     * The number of assets to skip.
     * [Optional]
     * Default: 0
     * @var int|null $skip
     */
    public $skip;

    /**
     * The maximum number of assets to return.
     * [Optional]
     * Default: 10
     * @var int|null $limit
     */
    public $limit;
}
