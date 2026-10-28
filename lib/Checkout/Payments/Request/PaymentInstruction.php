<?php

namespace Checkout\Payments\Request;

/**
 * Additional details about the instruction of a payout or an unreferenced refund.
 * Card payouts and unreferenced refunds use purpose and funds_transfer_type; bank payouts use scheme.
 */
class PaymentInstruction
{
    /**
     * The purpose of the card payout or unreferenced refund.
     * Required if the card's issuer_country is one of AR, BD, CL, CO, EG, IN or MX.
     * [Optional]
     * Enum: "family_support" "expatriation" "travel_and_tourism" "education" "medical_treatment"
     * "emergency_need" "leisure" "savings" "gifts" "donations" "financial_services" "it_services"
     * "investment" "insurance" "loan_payment" "pension" "royalties" "other" "income"
     * @var string
     */
    public $purpose;

    /**
     * The charge bearer of a bank payout.
     * [Optional]
     *
     * @deprecated Not in the current request specification; it only appears on the payout details
     * response. Retained for backwards compatibility and will be removed in a future version.
     * @var string
     */
    public $charge_bearer;

    /**
     * Whether the bank payout may be repaired.
     * [Optional]
     *
     * @deprecated Not in the current request specification; it only appears on the payout details
     * response. Retained for backwards compatibility and will be removed in a future version.
     * @var bool
     */
    public $repair;

    /**
     * An option to restrict the payment scheme type of a bank payout, when not available payouts will fail.
     * [Optional]
     * Enum: "local" "instant"
     * @var string value of InstructionScheme
     */
    public $scheme;

    /**
     * The FX quote identifier of a bank payout.
     * [Optional]
     *
     * @deprecated Not in the current request specification; it only appears on the payout details
     * response. Retained for backwards compatibility and will be removed in a future version.
     * @var string
     */
    public $quote_id;

    /**
     * The funds transfer type code for the type of card payout you're performing.
     * You can only use codes that have been assigned to you by the card schemes and Checkout.com, based
     * on your business case and requirements. If you have only been assigned one funds transfer type
     * code, this field is optional. For unreferenced refunds it is required if
     * processing.processing_speed is fast: use MI for Visa and C60 for Mastercard.
     * [Required] for card payouts, [Optional] for unreferenced refunds
     * @var string
     */
    public $funds_transfer_type;
}
