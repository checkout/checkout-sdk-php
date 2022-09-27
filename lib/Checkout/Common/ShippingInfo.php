<?php

namespace Checkout\Common;

class ShippingInfo
{
    /**
     * @var string
     */
    public $shipping_company;

    /**
     * @var string
     */
    public $shipping_method;

    /**
     * @var string
     */
    public $tracking_number;

    /**
     * @var string
     */
    public $tracking_uri;

    /**
     * @var string
     */
    public $return_shipping_company;

    /**
     * @var string
     */
    public $return_tracking_number;

    /**
     * @var string
     */
    public $return_tracking_uri;
}
