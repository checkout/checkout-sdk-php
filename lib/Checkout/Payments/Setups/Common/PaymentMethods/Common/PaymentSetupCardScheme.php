<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\Common;

/**
 * The supported card scheme names returned in the schemes list of the card, Apple Pay and Google Pay
 * payment methods.
 */
class PaymentSetupCardScheme
{
    /** @var string */
    public static $accel = "accel";

    /** @var string */
    public static $amex = "amex";

    /**
     * Not listed for Google Pay.
     * @var string
     */
    public static $cartes_bancaires = "cartes_bancaires";

    /** @var string */
    public static $china_union_pay = "china_union_pay";

    /** @var string */
    public static $diners = "diners";

    /** @var string */
    public static $discover = "discover";

    /** @var string */
    public static $jcb = "jcb";

    /** @var string */
    public static $mada = "mada";

    /** @var string */
    public static $maestro = "maestro";

    /** @var string */
    public static $mastercard = "mastercard";

    /** @var string */
    public static $nyce = "nyce";

    /** @var string */
    public static $omannet = "omannet";

    /** @var string */
    public static $pulse = "pulse";

    /** @var string */
    public static $shazam = "shazam";

    /** @var string */
    public static $star = "star";

    /** @var string */
    public static $upi = "upi";

    /** @var string */
    public static $visa = "visa";
}
