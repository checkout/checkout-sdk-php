<?php

namespace Checkout\Common;

/**
 * The type of payment instrument.
 *
 * The current API declares bacs, bank_account, card, token, sepa and ach. This class also carries
 * card_token, which only the previous API (ABC) accepts.
 */
class InstrumentType
{
    public static $bank_account = "bank_account";

    public static $token = "token";

    public static $card = "card";

    public static $sepa = "sepa";

    public static $ach = "ach";

    public static $bacs = "bacs";

    // ========================================
    // Previous API (ABC) only - not declared by the current API (NAS)
    // ========================================

    /**
     * <b>Previous API (ABC) only.</b> The current API's instrument type does not declare this value.
     * Only use it when working with Previous API accounts.
     */
    public static $card_token = "card_token";
}
