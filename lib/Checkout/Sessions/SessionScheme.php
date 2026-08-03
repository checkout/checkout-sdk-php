<?php

namespace Checkout\Sessions;

/**
 * The card scheme.
 *
 * Used by SessionSource::$scheme to indicate the cardholder scheme choice, and returned by the
 * session responses to indicate the scheme the authentication was carried out against.
 *
 * [Optional]
 */
final class SessionScheme
{
    /**
     * American Express.
     * @var string
     */
    public static $amex = "amex";

    /**
     * Cartes Bancaires.
     * @var string
     */
    public static $cartes_bancaires = "cartes_bancaires";

    /**
     * Diners Club.
     * @var string
     */
    public static $diners = "diners";

    /**
     * Discover.
     * @var string
     */
    public static $discover = "discover";

    /**
     * JCB.
     * @var string
     */
    public static $jcb = "jcb";

    /**
     * Mastercard.
     * @var string
     */
    public static $mastercard = "mastercard";

    /**
     * Unified Payments Interface (UPI).
     * @var string
     */
    public static $upi = "upi";

    /**
     * Visa.
     * @var string
     */
    public static $visa = "visa";
}
