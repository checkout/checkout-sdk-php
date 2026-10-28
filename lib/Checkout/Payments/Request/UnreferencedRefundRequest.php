<?php

namespace Checkout\Payments\Request;

use Checkout\Payments\Destination\PaymentRequestDestination;
use Checkout\Payments\Request\Source\PayoutRequestCurrencyAccountSource;

/**
 * An unreferenced refund request, sent to POST /payments.
 * Refunds a card without a reference to a previous payment, funded from a currency account.
 */
class UnreferencedRefundRequest
{
    public function __construct()
    {
        $this->payment_type = "UnreferencedRefund";
    }

    /**
     * The source of the unreferenced refund.
     * [Required]
     * Accepted keys: type ("currency_account"), id (the ID of the currency account that will fund
     * the unreferenced refund, ^(ca)_(\w{26})$, min 29 characters, max 29 characters).
     * @var PayoutRequestCurrencyAccountSource
     */
    public $source;

    /**
     * The destination of the unreferenced refund.
     * You can encrypt this field by using the Cko-Encryption-Key-Id and Cko-Encrypted-Properties headers.
     * [Required]
     * Discriminated by type: "card" "network_token" "id" "token".
     * Destinations: PaymentRequestCardDestination, PaymentRequestNetworkTokenDestination,
     * PaymentRequestIdDestination, PaymentRequestTokenDestination.
     * All in the Checkout\Payments\Destination namespace.
     * @var PaymentRequestDestination
     */
    public $destination;

    /**
     * Additional details about the unreferenced refund instruction.
     * [Optional]
     * Accepted keys: funds_transfer_type, purpose.
     * @var PaymentInstruction|null
     */
    public $instruction;

    /**
     * The amount in minor currency.
     * [Required]
     * Format: int64
     * min 1
     * max 9999999999
     * @var int
     */
    public $amount;

    /**
     * The three-letter ISO currency code of the payment.
     * [Required]
     * min 3 characters
     * max 3 characters
     * @var string value of Currency
     */
    public $currency;

    /**
     * The type of the payment. Set to "UnreferencedRefund" by the constructor.
     * [Required]
     * Enum: "UnreferencedRefund"
     * @var string
     */
    public $payment_type;

    /**
     * The processing channel identifier.
     * [Required]
     * ^(pc)_(\w{26})$
     * @var string
     */
    public $processing_channel_id;

    /**
     * An internal reference you can later use to identify this payment.
     * [Optional]
     * @var string|null
     */
    public $reference;

    /**
     * A set of key-value pairs that you can attach to the refund request. It can be useful for
     * storing additional information in a structured format.
     * Note: This object only allows one level of depth, so cannot accept non-primitive data types
     * such as objects or arrays.
     * [Optional]
     * @var array|null
     */
    public $metadata;

    /**
     * The previous related payment identifier. This could be the ID of the payment that you want
     * to refund.
     * [Optional]
     * @var string|null
     */
    public $previous_payment_id;

    /**
     * The dimension details about business segment for payment request.
     * At least one dimension required.
     * [Optional]
     * Accepted keys: brand, business_category, market (max 50 characters each).
     * @var PaymentSegment|null
     */
    public $segment;

    /**
     * Returns information related to the processing of the payment.
     * [Optional]
     * Accepted keys: processing_speed (Enum: "fast").
     * @var CardPayoutProcessing|null
     */
    public $processing;
}
