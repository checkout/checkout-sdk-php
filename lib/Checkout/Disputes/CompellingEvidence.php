<?php

namespace Checkout\Disputes;

class CompellingEvidence
{
    /**
     * Value can be either "Merchandise" or "Services".
     * [Required]
     * @var string $merchandise_or_service
     */
    public $merchandise_or_service;

    /**
     * Description of the merchandise or service that this transaction was for.
     * [Required]
     * min 1 characters
     * max 5000 characters
     * @var string $merchandise_or_service_desc
     */
    public $merchandise_or_service_desc;

    /**
     * The date and time the merchandise or service was provided, in UTC format.
     * [Required]
     * Format: date-time
     * @var string $merchandise_or_service_provided_date
     */
    public $merchandise_or_service_provided_date;

    /**
     * Status of the order. Only valid if merchandise_or_service is equal to "Merchandise".
     * [Optional]
     * @var string|null $shipping_delivery_status
     */
    public $shipping_delivery_status;

    /**
     * Tracking of the order. Only valid if merchandise_or_service is equal to "Merchandise".
     * [Optional]
     * max 50 characters
     * @var string|null $tracking_information
     */
    public $tracking_information;

    /**
     * User ID is synonymous to Customer Account/log in ID.
     * [Optional]
     * max 50 characters
     * @var string|null $user_id
     */
    public $user_id;

    /**
     * IpAddress used in the transaction. Should be either IPV4 or IPV6.
     * [Optional]
     * @var string|null $ip_address
     */
    public $ip_address;

    /**
     * Device used in the transaction.
     * [Optional]
     * min 15 characters
     * max 64 characters
     * @var string|null $device_id
     */
    public $device_id;

    /**
     * The shipping address provided for this transaction.
     * [Optional]
     * @var array|null $shipping_address
     */
    public $shipping_address;

    /**
     * List of historical transactions. At least two transactions.
     * [Required]
     * @var array $historical_transactions
     */
    public $historical_transactions;
}
