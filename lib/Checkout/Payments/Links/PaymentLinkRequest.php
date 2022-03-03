<?php

namespace Checkout\Payments\Links;

use Checkout\Common\CustomerRequest;
use Checkout\Payments\BillingDescriptor;
use Checkout\Payments\BillingInformation;
use Checkout\Payments\PaymentRecipient;
use Checkout\Payments\ProcessingSettings;
use Checkout\Payments\RiskRequest;
use Checkout\Payments\ShippingDetails;
use Checkout\Payments\ThreeDsRequest;
use DateTime;

class PaymentLinkRequest
{

    public int $amount;

    public string $currency;

    public string $reference;

    public string $description;

    public int $expires_in;

    public CustomerRequest $customer;

    public ShippingDetails $shipping;

    public BillingInformation $billing;

    public PaymentRecipient $recipient;

    public ProcessingSettings $processing;

    // Product
    public array $products;

    public RiskRequest $risk;

    public string $return_url;

    public array $metadata;

    public string $locale;

    public ThreeDsRequest $three_ds;

    public bool $capture;

    public DateTime $capture_on;

    public string $payment_type;

    public string $payment_ip;

    public BillingDescriptor $billing_descriptor;

    public array $allow_payment_methods;

}
