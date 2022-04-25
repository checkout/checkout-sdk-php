<?php

namespace Checkout\Sessions\Source;

use Checkout\Sessions\SessionSourceType;

class SessionsRequestIdSource extends SessionSource
{
    public function __construct()
    {
        parent::__construct(SessionSourceType::$id);
    }

    /**
     * @var string
     */
    public $id;
}
