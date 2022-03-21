<?php

namespace Checkout\Payments\Hosted;

class HostedPaymentsSessionRequest
{
    public $amount;

    public $currency;

    public $reference;

    public $description;

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

    public $metadata;

    // ThreeDsRequest
    public $three_ds;

    // RiskRequest
    public $risk;

    public $success_url;

    public $cancel_url;

    public $failure_url;

    public $locale;

    public $capture;

    // DateTime
    public $capture_on;

    public $payment_type;

    public $payment_ip;

    // BillingDescriptor
    public $billing_descriptor;

    //PaymentSourceType
    public $allow_payment_methods;

    // Only available in Four

    public $processing_channel_id;

    // MarketplaceData
    public $marketplace;

}
