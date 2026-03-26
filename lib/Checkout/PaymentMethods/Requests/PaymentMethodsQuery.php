<?php

namespace Checkout\PaymentMethods\Requests;

use Checkout\Common\AbstractQueryFilter;

class PaymentMethodsQuery extends AbstractQueryFilter
{
    /**
     * The processing channel to be used for payment methods retrieval. (Required)
     * Pattern: ^(pc)_(\w{26})$
     *
     * @var string
     */
    public $processing_channel_id;
}
