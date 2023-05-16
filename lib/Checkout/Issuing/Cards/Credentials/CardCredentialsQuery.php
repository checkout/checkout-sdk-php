<?php

namespace Checkout\Issuing\Cards\Credentials;

use Checkout\Common\AbstractQueryFilter;

class CardCredentialsQuery extends AbstractQueryFilter
{
    /**
     * @var string
     */
    public $credentials;
}
