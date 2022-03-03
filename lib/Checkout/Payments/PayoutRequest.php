<?php

namespace Checkout\Payments;

use Checkout\Common\CustomerRequest;
use Checkout\Payments\Destination\PaymentRequestDestination;
use DateTime;

class PayoutRequest
{
    public PaymentRequestDestination $destination;

    public int $amount;

    public string $currency;

    public string $payment_type;

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

    public string $purpose;

    public PaymentRecipient $recipient;

    public array $metadata;

    public array $processing;

}
