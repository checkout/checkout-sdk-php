<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\Instrument;

use Checkout\Payments\Setups\Common\PaymentMethods\Common\PaymentSetupPaymentMethod;

/**
 * The instrument payment method's details and configuration. Serialized under the key "instrument".
 *
 * Inherits status and flags from PaymentSetupPaymentMethod. Send the id of a stored instrument; type and
 * action are returned by the API only.
 */
class PaymentSetupInstrument extends PaymentSetupPaymentMethod
{
    /**
     * The unique identifier of the selected instrument.
     * [Optional]
     * @var string
     */
    public $id;

    /**
     * The type of the instrument, for example bacs.
     * [Optional]
     * readOnly
     * @var string
     */
    public $type;

    /**
     * The next available action for the payment method.
     * [Optional]
     * readOnly
     * @var PaymentSetupInstrumentAction
     */
    public $action;
}
