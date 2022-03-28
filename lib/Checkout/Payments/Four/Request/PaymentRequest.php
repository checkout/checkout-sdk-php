<?php

namespace Checkout\Payments\Four\Request;

class PaymentRequest
{
    // AbstractRequestSource
    public $source;

    public $amount;

    public $currency;

    //AuthorizationType
    public $payment_type;

    public $merchant_initiated;

    public $reference;

    public $description;

    public $authorization_type;

    public $capture;

    // DateTime
    public $capture_on;

    // CustomerRequest
    public $customer;

    // BillingDescriptor
    public $billing_descriptor;

    // ShippingDetails
    public $shipping;

    // ThreeDsRequest
    public $three_ds;

    public $processing_channel_id;

    public $previous_payment_id;

    // RiskRequest
    public $risk;

    public $success_url;

    public $failure_url;

    public $payment_ip;

    // PaymentSender
    public $sender;

    // PaymentRecipient
    public $recipient;

    // MarketplaceData
    public $marketplace;

    // ProcessingSettings
    public $processing;

    public $metadata;

    // array Four/Product
    public $items;

}
