<?php

namespace Checkout\Apm\Klarna;

class KlarnaShippingInfo
{
    public string $shipping_company;

    public string $shipping_method;

    public string $tracking_number;

    public string $tracking_uri;

    public string $return_shipping_company;

    public string $return_tracking_number;

    public string $return_tracking_uri;
}
