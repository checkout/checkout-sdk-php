<?php

namespace Checkout\Payments\Sender;

/**
 * A sender of type "instrument", used by bank payouts and payments (not by card payouts).
 * For bank payouts, the sender parameters are retrieved from the destination. For payments, the sender
 * information (name and address) is retrieved from the payment instrument.
 * Only type and reference are part of this sender: reference is [Required] for bank payouts and
 * [Optional] for payments.
 */
class PaymentInstrumentSender extends PaymentSender
{
    public function __construct()
    {
        parent::__construct(PaymentSenderType::$instrument);
    }

}
