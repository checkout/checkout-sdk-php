<?php

namespace Checkout\Payments\Hosted;

use Checkout\Common\CustomerRequest;
use Checkout\Common\MarketplaceData;
use Checkout\Payments\BillingDescriptor;
use Checkout\Payments\BillingInformation;
use Checkout\Payments\PaymentRecipient;
use Checkout\Payments\ProcessingSettings;
use Checkout\Payments\RiskRequest;
use Checkout\Payments\ShippingDetails;
use Checkout\Payments\ThreeDsRequest;
use DateTime;

class HostedPaymentsSessionRequest
{
    public int $amount;

    public string $currency;

    public string $reference;

    public string $description;

    public CustomerRequest $customer;

    public ShippingDetails $shipping;

    public BillingInformation $billing;

    public PaymentRecipient $recipient;

    public ProcessingSettings $processing;

    // Product
    public array $products;

    public array $metadata;

    public ThreeDsRequest $three_ds;

    public RiskRequest $risk;

    public string $success_url;

    public string $cancel_url;

    public string $failure_url;

    public string $locale;

    public bool $capture;

    public DateTime $capture_on;

    public string $payment_type;

    public string $payment_ip;

    public BillingDescriptor $billing_descriptor;

    //PaymentSourceType
    public array $allow_payment_methods;

    // Only available in Four

    public string $processing_channel_id;

    public MarketplaceData $marketplace;

}
