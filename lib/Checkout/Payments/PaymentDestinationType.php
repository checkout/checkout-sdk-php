<?php

namespace Checkout\Payments;

/**
 * The payout destination type, the discriminator of the payout destination.
 * Card payout destinations: "card" "network_token" "id" "token"
 * Bank payout destinations: "id" "bank_account"
 */
class PaymentDestinationType
{
    public static $bank_account = "bank_account";
    public static $card = "card";
    public static $id = "id";
    public static $network_token = "network_token";
    public static $token = "token";
}
