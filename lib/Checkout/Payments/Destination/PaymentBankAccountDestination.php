<?php

namespace Checkout\Payments\Destination;

use Checkout\Common\AccountHolder;
use Checkout\Common\BankDetails;
use Checkout\Payments\PaymentDestinationType;

/**
 * A bank account payout destination.
 * Bank payout destination with type "bank_account".
 */
class PaymentBankAccountDestination extends PaymentRequestDestination
{

    public function __construct()
    {
        parent::__construct(PaymentDestinationType::$bank_account);
    }

    /**
     * The type of the bank account. Note that some beneficiary banks do not accept payouts to savings
     * accounts.
     * [Required]
     * Enum: "savings" "current" "cash"
     * @var string value of AccountType
     */
    public $account_type;

    /**
     * Number (which can contain letters) that identifies the local account.
     * [Optional]
     * @var string
     */
    public $account_number;

    /**
     * Code that identifies the bank. Also referred as Routing Number in the United States.
     * [Optional]
     * @var string
     */
    public $bank_code;

    /**
     * Code that identifies the bank branch.
     * [Optional]
     * @var string
     */
    public $branch_code;

    /**
     * The combination of bank code and/or branch code and account number.
     * [Optional]
     * @var string
     */
    public $bban;

    /**
     * Internationally agreed standard for identifying bank accounts. Lengths vary per destination_country.
     * [Optional]
     * @var string
     */
    public $iban;

    /**
     * 8 or 11 character code which identifies the bank or bank branch.
     * [Optional]
     * @var string
     */
    public $swift_bic;

    /**
     * The two-letter ISO country code of where the account is based. Note that not all countries are
     * available.
     * [Required]
     * min 2 characters
     * max 2 characters
     * @var string value of Country
     */
    public $country;

    /**
     * The account holder details.
     * [Optional]
     * @var AccountHolder
     */
    public $account_holder;

    /**
     * The bank details.
     * [Optional]
     *
     * @deprecated Not in the request schema of the bank account payout destination; bank only exists on the
     * Instruments bank account schemas. Retained for backwards compatibility and will be removed in the next
     * major version. There is no replacement.
     * @var BankDetails
     */
    public $bank;
}
