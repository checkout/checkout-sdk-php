<?php

namespace Checkout\Payments\Request;

/**
 * Configures the parameters for customer retries.
 *
 * A customer retry is a payment attempt performed by the customer on the checkout page. This differs from
 * authorization request retries performed in the back end by Checkout.com.
 */
class CustomerRetry
{
    /**
     * The maximum number of customer retry attempts, excluding the initial payment attempt.
     * [Optional] (required by the payment sessions endpoint when customer_retry is sent)
     * default 5
     * min 0
     * max 5
     * @var int|null $max_attempts
     */
    public $max_attempts;
}
