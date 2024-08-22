<?php

namespace Checkout\Common;

final class ChallengeIndicatorType
{
    public static $challenge_requested = "challenge_requested";
    public static $challenge_requested_mandate = "challenge_requested_mandate";
    public static $data_share = "data_share";
    public static $low_value = "low_value";
    public static $no_challenge_requested = "no_challenge_requested";
    public static $no_preference = "no_preference";
    public static $transaction_risk_assessment = "transaction_risk_assessment";
    public static $trusted_listing = "trusted_listing";
    public static $trusted_listing_prompt = "trusted_listing_prompt";
}
