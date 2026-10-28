<?php

namespace Checkout\Payments\Request;

/**
 * The dimension details about business segment for payment request. At least one dimension required.
 */
class PaymentSegment
{
    /**
     * The brand of business segment.
     * [Optional]
     * max 50 characters
     * @var string
     */
    public $brand;

    /**
     * The category of business segment.
     * [Optional]
     * max 50 characters
     * @var string
     */
    public $business_category;

    /**
     * The market of business segment.
     * [Optional]
     * max 50 characters
     * @var string
     */
    public $market;
}
