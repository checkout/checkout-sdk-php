<?php

namespace Checkout\Sessions\Source;

use Checkout\Sessions\SessionSourceType;

class RequestNetworkTokenSource extends SessionSource
{
    public function __construct()
    {
        parent::__construct(SessionSourceType::$network_token);
    }

    /**
     * @var string
     */
    public $token;

    /**
     * @var int
     */
    public $expiry_month;

    /**
     * @var int
     */
    public $expiry_year;

    /**
     * @var string
     */
    public $name;

    /**
     * @var bool
     */
    public $stored;
}
