<?php

namespace Checkout\Common;

/**
 * Indicates the preference for whether or not a 3DS challenge should be performed. The customer's
 * bank has the final say on whether or not the customer receives the challenge.
 *
 * This is the four-value indicator accepted by the 3ds.challenge_indicator field on POST /payments,
 * POST /hosted-payments, POST /payment-links and POST /payment-sessions.
 *
 * The five exemption values ($low_value, $trusted_listing, $trusted_listing_prompt,
 * $transaction_risk_assessment and $data_share) are deprecated here: they are accepted only by
 * POST /sessions and are rejected by the fields that use this class. They are kept for backwards
 * compatibility. Use Checkout\Sessions\SessionChallengeIndicatorType for sessions.
 *
 * [Optional]
 * Default: $no_preference
 *
 * @see \Checkout\Sessions\SessionChallengeIndicatorType
 */
final class ChallengeIndicatorType
{
    /**
     * A challenge is requested for this payment.
     * @var string
     */
    public static $challenge_requested = "challenge_requested";

    /**
     * A challenge is requested for this payment because it is mandated by local regulation or
     * scheme rules.
     * @var string
     */
    public static $challenge_requested_mandate = "challenge_requested_mandate";

    /**
     * Indicates a data-share authentication request.
     * @var string
     * @deprecated Only valid for POST /sessions. Use
     * Checkout\Sessions\SessionChallengeIndicatorType::$data_share instead. This value is rejected
     * by the 3ds.challenge_indicator fields that use this class.
     */
    public static $data_share = "data_share";

    /**
     * Request a low-value exemption.
     * @var string
     * @deprecated Only valid for POST /sessions. Use
     * Checkout\Sessions\SessionChallengeIndicatorType::$low_value instead. This value is rejected
     * by the 3ds.challenge_indicator fields that use this class.
     */
    public static $low_value = "low_value";

    /**
     * A challenge is not requested for this payment.
     * @var string
     */
    public static $no_challenge_requested = "no_challenge_requested";

    /**
     * No preference as to whether a challenge should be performed. This is the default.
     * @var string
     */
    public static $no_preference = "no_preference";

    /**
     * Request a transaction risk analysis (TRA) exemption.
     * @var string
     * @deprecated Only valid for POST /sessions. Use
     * Checkout\Sessions\SessionChallengeIndicatorType::$transaction_risk_assessment instead. This
     * value is rejected by the 3ds.challenge_indicator fields that use this class.
     */
    public static $transaction_risk_assessment = "transaction_risk_assessment";

    /**
     * Request a trusted listing exemption.
     * @var string
     * @deprecated Only valid for POST /sessions. Use
     * Checkout\Sessions\SessionChallengeIndicatorType::$trusted_listing instead. This value is
     * rejected by the 3ds.challenge_indicator fields that use this class.
     */
    public static $trusted_listing = "trusted_listing";

    /**
     * Request a trusted listing prompt to add the merchant to the cardholder's trusted list.
     * @var string
     * @deprecated Only valid for POST /sessions. Use
     * Checkout\Sessions\SessionChallengeIndicatorType::$trusted_listing_prompt instead. This value
     * is rejected by the 3ds.challenge_indicator fields that use this class.
     */
    public static $trusted_listing_prompt = "trusted_listing_prompt";
}
