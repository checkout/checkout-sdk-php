<?php

namespace Checkout\Sessions;

class InitialTransaction
{
    /**
     * @var string
     */
    public $acs_transaction_id;

    /**
     * @var string
     */
    public $authentication_method;

    /**
     * @var string
     */
    public $authentication_timestamp;

    /**
     * @var string
     */
    public $authentication_data;

    /**
     * @var string
     */
    public $initial_session_id;
}
