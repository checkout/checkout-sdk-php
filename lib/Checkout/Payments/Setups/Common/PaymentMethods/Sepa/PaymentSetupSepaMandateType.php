<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\Sepa;

/**
 * The type of SEPA mandate on a Payment Setup.
 *
 * The wire values are lowercase. Do not use Checkout\Instruments\SepaMandateType, which serializes Core
 * and B2B.
 */
class PaymentSetupSepaMandateType
{
    /** @var string */
    public static $core = "core";

    /** @var string */
    public static $b2b = "b2b";
}
