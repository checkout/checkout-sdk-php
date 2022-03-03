<?php

namespace Checkout\Sessions;

class TransactionType
{
    public static string $goods_service = "goods_service";
    public static string $check_acceptance = "check_acceptance";
    public static string $account_funding = "account_funding";
    public static string $quashi_card_transaction = "quashi_card_transaction";
    public static string $prepaid_activation_and_load = "prepaid_activation_and_load";
}
