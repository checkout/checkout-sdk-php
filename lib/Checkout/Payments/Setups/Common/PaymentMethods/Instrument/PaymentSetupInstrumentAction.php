<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\Instrument;

use Checkout\Common\EmptyAsObject;

/**
 * The next available action for the instrument payment method (response only).
 */
#[EmptyAsObject]
class PaymentSetupInstrumentAction
{
    /**
     * The type of action.
     * [Optional]
     * readOnly
     * Enum: "set_instrument"
     * @var string value of PaymentSetupInstrumentActionType
     */
    public $type;
}
