<?php

namespace Checkout\Payments\Sender;

use Checkout\Common\AccountHolderIdentification;
use Checkout\Common\Address;

/**
 * A sender of type "corporate", used by card payouts, bank payouts and payments.
 * Card payouts use company_name, address, reference, reference_type, source_of_funds and
 * identification. Bank payouts and payments use reference, company_name and address.
 */
class PaymentCorporateSender extends PaymentSender
{
    public function __construct()
    {
        parent::__construct(PaymentSenderType::$corporate);
    }

    /**
     * The sender's company name. For card payouts, it must be a valid legal name (a single character
     * or all non-alphabetic characters return a field validation error).
     * [Required]
     * max 35 characters for card payouts
     * @var string
     */
    public $company_name;

    /**
     * The sender's address. For card payouts, the sender's registered corporate address.
     * [Required]
     * For card payouts, address_line1, city and country are required; state is required if the
     * country is US or CA; zip is required if the country is US.
     * @var Address
     */
    public $address;

    /**
     * The type of identifier used as the reference.
     * Card payouts only.
     * [Required] for card payouts
     * Enum: "other"
     * @var string
     */
    public $reference_type;

    /**
     * The source of the funds used to fund the card payout.
     * Card payouts only.
     * [Required] for card payouts
     * Enum: "credit" "debit" "prepaid" "deposit_account" "mobile_money_account" "cash"
     * @var string
     */
    public $source_of_funds;

    /**
     * The sender's identification. Required if the card's issuer_country is one of AR, BR, CO or PE.
     * Card payouts only.
     * [Optional]
     * @var AccountHolderIdentification
     */
    public $identification;
}
