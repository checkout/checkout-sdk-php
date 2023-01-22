<?php

namespace Checkout\Payments\Request\Source;

class PayoutRequestEntitySource extends PayoutRequestSource
{
    public function __construct()
    {
        parent::__construct(PayoutSourceType::$entity);
    }

    /**
     * @var string
     */
    public $id;
}
