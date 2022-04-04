<?php

namespace Checkout\Payments\Links;

class PaymentLinkRequest
{

    public $amount;

    public $currency;

    public $reference;

    public $description;

    public $expires_in;

    // CustomerRequest
    public $customer;

    // ShippingDetails
    public $shipping;

    // BillingInformation
    public $billing;

    // PaymentRecipient
    public $recipient;

    // ProcessingSettings
    public $processing;

    // Product
    public $products;

    // RiskRequest
    public $risk;

    public $return_url;

    public $metadata;

    public $locale;

    // ThreeDsRequest
    public $three_ds;

    public $capture;

    // DateTime
    public $capture_on;

    public $payment_type;

    public $payment_ip;

    // BillingDescriptor
    public $billing_descriptor;

    public $allow_payment_methods;

    // Only available in Four

    public $processing_channel_id;

    // MarketplaceData
    public $marketplace;
}
