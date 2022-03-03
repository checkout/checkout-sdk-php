<?php

namespace Checkout\Payments;

use Checkout\Common\CustomerRequest;
use Checkout\Payments\Source\AbstractRequestSource;
use DateTime;

class PaymentRequest
{
    public AbstractRequestSource $source;

    public int $amount;

    public string $currency;

    public string $payment_type;

    public bool $merchant_initiated;

    public string $reference;

    public string $description;

    public bool $capture;

    public DateTime $capture_on;

    public CustomerRequest $customer;

    public BillingDescriptor $billing_descriptor;

    public ShippingDetails $shipping;

    public string $previous_payment_id;

    public RiskRequest $risk;

    public string $success_url;

    public string $failure_url;

    public string $payment_ip;

    public ThreeDsRequest $three_ds;

    public PaymentRecipient $recipient;

    public array $metadata;

    public array $processing;

}
