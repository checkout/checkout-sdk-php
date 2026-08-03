<?php

namespace Checkout\Sessions;

/**
 * Identifies the type of transaction being authenticated.
 *
 * Used by SessionRequest::$transaction_type.
 *
 * [Optional]
 * Default: $goods_service
 * max 50 characters
 */
class TransactionType
{
    /**
     * A transaction that funds an account.
     * @var string
     */
    public static $account_funding = "account_funding";

    /**
     * A transaction that accepts a check.
     * @var string
     */
    public static $check_acceptance = "check_acceptance";

    /**
     * A transaction for goods or a service. This is the default.
     * @var string
     */
    public static $goods_service = "goods_service";

    /**
     * A transaction that activates or loads a prepaid card.
     * @var string
     */
    public static $prepaid_activation_and_load = "prepaid_activation_and_load";

    /**
     * A quasi-cash transaction, for example the purchase of casino chips, money orders or
     * traveller's cheques.
     * @var string
     */
    public static $quasi_card_transaction = "quasi_card_transaction";
}
