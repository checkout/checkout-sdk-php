<?php

namespace Checkout\tests\Helpers;

use Checkout\Models\Tokens\ApplePay;
use Checkout\Models\Tokens\ApplePayHeader;
use Checkout\Models\Tokens\Card;
use Checkout\Models\Tokens\GooglePay;

class Tokens
{
    public static function generateID()
    {
        return 'tok_' . substr(md5(rand()), 0, 26);
    }

    public static function generateCardModel()
    {
        $card = new Card('4242424242424242', 01, 2025);
        $card->cvv = 100;
        $card->name = 'Joe Smith';

        return $card;
    }

    public static function generateGooglePayModel()
    {
        return new GooglePay('{protocol_version}', '{signature}', '{signed_message}');
    }

    public static function generateApplePayModel()
    {
        $header = new ApplePayHeader('{transaction_id}', '{public_key_hash}', '{ephemeral_public_key}');
        return new ApplePay('{version}', '{signature}', '{data}', $header);
    }
}
