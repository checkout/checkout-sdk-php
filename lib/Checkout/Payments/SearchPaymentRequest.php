<?php

namespace Checkout\Payments;

class SearchPaymentRequest
{
    /**
     * @var string
     */
    public $query;

    /**
     * @var int
     */
    public $limit;

    /**
     * @var string
     */
    public $from;

    /**
     * @var string
     */
    public $to;
}
