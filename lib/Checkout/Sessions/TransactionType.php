<?php

namespace Checkout\Sessions;

class TransactionType
{
    public static $goods_service = "goods_service";
    public static $check_acceptance = "check_acceptance";
    public static $account_funding = "account_funding";
    public static $quashi_card_transaction = "quashi_card_transaction";
    public static $prepaid_activation_and_load = "prepaid_activation_and_load";
}
