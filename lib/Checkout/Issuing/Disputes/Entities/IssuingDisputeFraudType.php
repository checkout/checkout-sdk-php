<?php

namespace Checkout\Issuing\Disputes\Entities;

class IssuingDisputeFraudType
{
    public static $card_lost = "card_lost";
    public static $card_stolen = "card_stolen";
    public static $card_never_received = "card_never_received";
    public static $fraudulent_account = "fraudulent_account";
    public static $counterfeit_card = "counterfeit_card";
    public static $account_takeover = "account_takeover";
    public static $card_not_present_fraud = "card_not_present_fraud";
    public static $merchant_misrepresentation = "merchant_misrepresentation";
    public static $cardholder_manipulation = "cardholder_manipulation";
    public static $incorrect_processing = "incorrect_processing";
    public static $other = "other";
}
