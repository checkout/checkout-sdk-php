<?php

namespace Checkout\Apm\Bacs;

/**
 * Bacs Direct Debit notification request.
 */
class BacsNotificationRequest
{
    /**
     * The ID of the Bacs Direct Debit instrument to notify against.
     * [Required]
     * Pattern: ^(src)_(\w{26})$
     * @var string
     */
    public $source_id;

    /**
     * The type of pre-notification being sent to the payer.
     * [Required]
     * @var string values of Checkout\Apm\Bacs\BacsNotificationType
     */
    public $notification_type;

    /**
     * The date the funds will be collected from the payer's account, in the format yyyy-MM-dd.
     * [Required]
     * Format: yyyy-MM-dd
     * @var string
     */
    public $collection_date;

    /**
     * The amount to be collected, in the currency's minor unit.
     * [Required]
     * min 1
     * @var int
     */
    public $amount;

    /**
     * The three-letter ISO 4217 currency code of the collection.
     * [Required]
     * min 3 characters, max 3 characters
     * @var string values of Checkout\Common\Currency
     */
    public $currency;

    /**
     * A reference you can use to identify the collection.
     * [Optional]
     * max 50 characters
     * @var string
     */
    public $reference;

    /**
     * The email address of the payer that the pre-notification is sent to.
     * [Required]
     * Format: email
     * @var string
     */
    public $customer_email;

    /**
     * The billing descriptor that appears on the payer's bank statement.
     * [Required]
     * max 25 characters
     * @var string
     */
    public $billing_descriptor;

    /**
     * The support email address included in the pre-notification.
     * [Required]
     * Format: email
     * @var string
     */
    public $support_email;

    /**
     * The support phone number included in the pre-notification, in E.164 format.
     * [Optional]
     * @var string
     */
    public $support_phone;
}
