<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\Common;

/**
 * Account holder details for the payment. Used by the card, Apple Pay and Google Pay payment methods.
 */
class CardAccountHolder
{
    /**
     * The card account holder type.
     * [Optional]
     * Enum: "individual" "government" "corporate"
     * @var string value of AccountHolderType
     */
    public $type;

    /**
     * The card account holder's first name.
     * [Optional]
     * @var string
     */
    public $first_name;

    /**
     * The card account holder's last name.
     * [Optional]
     * @var string
     */
    public $last_name;

    /**
     * The card account holder's middle name.
     * [Optional]
     * @var string
     */
    public $middle_name;

    /**
     * The card account holder's company name.
     * [Optional]
     * @var string
     */
    public $company_name;

    /**
     * Indicates whether the ANI (account name inquiry) check is performed with the card scheme (Visa or
     * Mastercard).
     * [Optional]
     * @var bool
     */
    public $account_name_inquiry;
}
