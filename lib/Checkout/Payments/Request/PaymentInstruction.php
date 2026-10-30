<?php

namespace Checkout\Payments\Request;

use Checkout\Payments\InstructionRemittance;

/**
 * Additional details about the instruction of a payout, an unreferenced refund or a payment.
 * Card payouts and unreferenced refunds use purpose and funds_transfer_type; bank payouts use scheme and
 * remittance. Hosted payments, payment links and payment sessions use purpose only.
 * Regular payments use Checkout\Payments\PaymentInstruction instead.
 */
class PaymentInstruction
{
    /**
     * The purpose of the card payout, unreferenced refund or payment.
     * For card payouts, required if the card's issuer_country is one of AR, BD, CL, CO, EG, IN, MX or SA.
     * For unreferenced refunds, required if the card's issuer_country is one of AR, BD, CL, CO, EG, IN or MX.
     * For hosted payments, payment links and payment sessions, it might be required for AFT transactions
     * depending on the card's issuer_country.
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
     * @deprecated Not in the request schema; still returned in PayoutDetails (instruction.charge_bearer).
     * Retained for backwards compatibility and will be removed in the next major version. There is no
     * replacement.
     * @var string
     */
    public $charge_bearer;

    /**
     * Whether the bank payout may be repaired.
     * [Optional]
     *
     * @deprecated Not in the request schema; still returned in PayoutDetails (instruction.repair).
     * Retained for backwards compatibility and will be removed in the next major version. There is no
     * replacement.
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
     * The remittance details of a bank payout, used for reconciliation.
     * [Optional]
     * @var InstructionRemittance|null
     */
    public $remittance;

    /**
     * The FX quote identifier of a bank payout.
     * [Optional]
     *
     * @deprecated Not in the request schema; still returned in PayoutDetails (instruction.quote_id).
     * Retained for backwards compatibility and will be removed in the next major version. There is no
     * replacement.
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
