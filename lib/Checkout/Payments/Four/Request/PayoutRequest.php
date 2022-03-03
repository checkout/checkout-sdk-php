<?php

namespace Checkout\Payments\Four\Request;

use Checkout\Payments\Four\Destination\PaymentRequestDestination;
use Checkout\Payments\Four\Request\Source\PayoutRequestSource;
use Checkout\Payments\Four\Sender\PaymentSender;

class PayoutRequest
{

    public PayoutRequestSource $source;

    public PaymentRequestDestination $destination;

    public int $amount;

    public string $currency;

    public string $reference;

    public PayoutBillingDescriptor $billing_descriptor;

    public PaymentSender $sender;

    public PaymentInstruction $instruction;

    public string $processing_channel_id;

}
