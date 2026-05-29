<?php

namespace Checkout\Payments\Request\Source;

use Checkout\Common\AccountHolder;
use Checkout\Common\Address;
use Checkout\Common\PaymentSourceType;
use Checkout\Common\Phone;

class RequestIdSource extends AbstractRequestSource
{

    public function __construct()
    {
        parent::__construct(PaymentSourceType::$id);
    }

    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $cvv;

    /**
     * @var string
     */
    public $payment_method;

    /**
     * The cardholder's billing address.
     * [Optional]
     * @var Address|null $billing_address
     */
    public $billing_address;

    /**
     * The cardholder's phone number.
     * [Optional]
     * @var Phone|null $phone
     */
    public $phone;

    /**
     * Information about the account holder of the payment instrument.
     * [Optional]
     * @var AccountHolder|null $account_holder
     */
    public $account_holder;

    /**
     * Specifies whether to use the Real-Time Account Updater to update the card information.
     * [Optional]
     * default true
     * @var bool|null $allow_update
     */
    public $allow_update;
}
