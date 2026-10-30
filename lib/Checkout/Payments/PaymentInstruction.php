<?php

namespace Checkout\Payments;

/**
 * The instruction of a regular payment (PaymentRequest) and of a payment session submit or complete request.
 * Regular payments, hosted payments, payment links and payment sessions use purpose only. scheme and
 * remittance are the bank payout instruction keys, also available on
 * Checkout\Payments\Request\PaymentInstruction.
 */
class PaymentInstruction
{
    /**
     * The purpose of the payment.
     * This field might be required for AFT transactions depending on the card's issuer_country.
     * [Optional]
     * Enum: "donations" "education" "emergency_need" "expatriation" "family_support" "financial_services"
     * "gifts" "income" "insurance" "investment" "it_services" "leisure" "loan_payment" "medical_treatment"
     * "other" "pension" "royalties" "savings" "travel_and_tourism"
     * @var string|null $purpose value of Purpose
     */
    public $purpose;

    /**
     * An option to restrict the payment scheme type; payouts will fail if unavailable.
     * [Optional]
     * Enum: "local" "instant"
     * @var string|null $scheme
     */
    public $scheme;

    /**
     * Remittance details for the payment, used for reconciliation.
     * [Optional]
     * @var InstructionRemittance|null $remittance
     */
    public $remittance;
}
