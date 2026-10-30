<?php

namespace Checkout\Payments\Sender;

use Checkout\Common\AccountHolderIdentification;
use Checkout\Common\Address;

/**
 * A sender of type "individual", used by card payouts, bank payouts and payments.
 * Card payouts use first_name, middle_name, last_name, address, reference, reference_type,
 * source_of_funds, identification, date_of_birth, country_of_birth and nationality.
 * Bank payouts use reference, first_name, last_name, dob, address and identification.
 * Payments use reference, first_name, last_name, date_of_birth, address and identification.
 */
class PaymentIndividualSender extends PaymentSender
{
    public function __construct()
    {
        parent::__construct(PaymentSenderType::$individual);
    }

    /**
     * The sender's first name. For card payouts, the combination of first_name, middle_name, and
     * last_name should exceed 3 letters in length, and it must be a valid legal name (a single
     * character or all non-alphabetic characters return a field validation error). For bank payouts,
     * the legal first name of the ultimate sender.
     * [Required]
     * max 50 characters for card payouts
     * @var string
     */
    public $first_name;

    /**
     * The sender's middle name. The combination of first_name, middle_name, and last_name should
     * exceed 3 letters in length.
     * Card payouts only.
     * [Optional]
     * max 50 characters
     * @var string
     */
    public $middle_name;

    /**
     * The sender's last name. For card payouts, the combination of first_name, middle_name, and
     * last_name must exceed 3 letters in length, and it must be a valid legal name (a single
     * character or all non-alphabetic characters return a field validation error). For bank payouts,
     * the legal last name of the ultimate sender.
     * [Required]
     * max 50 characters for card payouts
     * @var string
     */
    public $last_name;

    /**
     * The sender's date of birth in a YYYY-MM-DD format. Strongly recommended where available.
     * Bank payouts only; card payouts and payments use date_of_birth.
     * [Optional]
     * Format: date
     * max 10 characters
     * @var string
     */
    public $dob;

    /**
     * The sender's address. For card payouts, the sender's residential address.
     * [Required]
     * For card payouts, address_line1, city and country are required; state is required if the
     * country is US or CA; zip is required if the country is US.
     * @var Address
     */
    public $address;

    /**
     * The sender's identification.
     * For card payouts, required if the card's issuer_country is one of AR, BR, CO or PE.
     * [Optional]
     * @var AccountHolderIdentification
     */
    public $identification;

    /**
     * The type of identifier used as the sender.reference.
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
     * The sender's date of birth, in the format YYYY-MM-DD.
     * For card payouts, required for cross-border money transfers. Card payouts and payments only; bank
     * payouts use dob.
     * [Optional]
     * min 10 characters and max 10 characters for card payouts
     * max 10 characters for payments
     * @var string
     */
    public $date_of_birth;

    /**
     * The sender's country of birth, as a two-letter ISO country code.
     * Card payouts only.
     * [Optional]
     * min 2 characters
     * max 2 characters
     * @var string values of Country
     */
    public $country_of_birth;

    /**
     * The sender's nationality, as a two-letter ISO country code.
     * Card payouts only.
     * [Optional]
     * min 2 characters
     * max 2 characters
     * @var string values of Country
     */
    public $nationality;

}
