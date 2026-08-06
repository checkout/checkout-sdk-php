<?php

namespace Checkout\Sessions;

/**
 * Indicates whether a challenge is requested for this session.
 *
 * Used by SessionRequest::$challenge_indicator for POST /sessions. This is the only field in the
 * API that accepts the exemption values below; the 3ds.challenge_indicator field on payments,
 * hosted payments, payment links and payment sessions accepts only the first four values and is
 * modelled by Checkout\Common\ChallengeIndicatorType.
 *
 * The following are requests for exemption: $low_value, $trusted_listing, $trusted_listing_prompt
 * and $transaction_risk_assessment. If an exemption cannot be applied, then the value
 * $no_challenge_requested will be used instead.
 *
 * [Optional]
 * Default: $no_preference
 * max 50 characters
 */
final class SessionChallengeIndicatorType
{
    /**
     * No preference as to whether a challenge should be performed. This is the default.
     * @var string
     */
    public static $no_preference = "no_preference";

    /**
     * A challenge is not requested for this session.
     * @var string
     */
    public static $no_challenge_requested = "no_challenge_requested";

    /**
     * A challenge is requested for this session.
     * @var string
     */
    public static $challenge_requested = "challenge_requested";

    /**
     * A challenge is requested for this session because it is mandated by local regulation or
     * scheme rules.
     * @var string
     */
    public static $challenge_requested_mandate = "challenge_requested_mandate";

    /**
     * Request a low-value exemption. If the exemption cannot be applied, the value
     * $no_challenge_requested will be used instead.
     * @var string
     */
    public static $low_value = "low_value";

    /**
     * Request a trusted listing exemption, applied when the cardholder has already added the
     * merchant to their list of trusted beneficiaries. If the exemption cannot be applied, the
     * value $no_challenge_requested will be used instead.
     * @var string
     */
    public static $trusted_listing = "trusted_listing";

    /**
     * Request a trusted listing exemption and prompt the cardholder to add the merchant to their
     * list of trusted beneficiaries. If the exemption cannot be applied, the value
     * $no_challenge_requested will be used instead.
     * @var string
     */
    public static $trusted_listing_prompt = "trusted_listing_prompt";

    /**
     * Request a transaction risk analysis (TRA) exemption. If the exemption cannot be applied, the
     * value $no_challenge_requested will be used instead.
     * @var string
     */
    public static $transaction_risk_assessment = "transaction_risk_assessment";

    /**
     * Request a data-share authentication, where cardholder data is shared with the issuer to
     * support their risk assessment without requesting a challenge.
     * @var string
     */
    public static $data_share = "data_share";
}
