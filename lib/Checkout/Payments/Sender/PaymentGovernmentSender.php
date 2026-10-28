<?php

namespace Checkout\Payments\Sender;

use Checkout\Common\AccountHolderIdentification;
use Checkout\Common\Address;

/**
 * A sender of type "government", used by card payouts only.
 * The reference, inherited from PaymentSender, is [Required], min 5 characters and max 15 characters,
 * alphanumeric only.
 */
class PaymentGovernmentSender extends PaymentSender
{
    public function __construct()
    {
        parent::__construct(PaymentSenderType::$government);
    }

    /**
     * The sender's company name. This must be a valid legal name (a single character or all
     * non-alphabetic characters return a field validation error).
     * [Required]
     * max 35 characters
     * @var string
     */
    public $company_name;

    /**
     * The sender's registered corporate address.
     * [Required]
     * address_line1, city and country are required; state is required if the country is US or CA; zip
     * is required if the country is US.
     * @var Address
     */
    public $address;

    /**
     * The type of identifier used as the reference.
     * [Required]
     * Enum: "other"
     * @var string
     */
    public $reference_type;

    /**
     * The source of the funds used to fund the card payout.
     * [Required]
     * Enum: "credit" "debit" "prepaid" "deposit_account" "mobile_money_account" "cash"
     * @var string
     */
    public $source_of_funds;

    /**
     * The sender's identification. Required if the card's issuer_country is one of AR, BR, CO or PE.
     * [Optional]
     * @var AccountHolderIdentification
     */
    public $identification;
}
