<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\Instrument;

/**
 * The next available action for the instrument payment method (response only).
 */
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
