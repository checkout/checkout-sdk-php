<?php

namespace Checkout\Payments\Four\Request;

class PayoutRequest
{

    // PayoutRequestSource
    public $source;

    // PaymentRequestDestination
    public $destination;

    public $amount;

    public $currency;

    public $reference;

    // PayoutBillingDescriptor
    public $billing_descriptor;

    // PaymentSender
    public $sender;

    // PaymentInstruction
    public $instruction;

    public $processing_channel_id;

}
