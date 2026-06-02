<?php

namespace Checkout\Payments\Request;

use Checkout\Payments\Destination\PaymentRequestDestination;
use Checkout\Payments\Request\Source\PayoutRequestSource;
use Checkout\Payments\Sender\PaymentSender;

use Checkout\Payments\Request\CardPayoutItem;
use Checkout\Payments\Request\CardPayoutProcessing;
use Checkout\Payments\Request\PaymentSegment;

class PayoutRequest
{

    /**
     * @var PayoutRequestSource
     */
    public $source;

    /**
     * @var PaymentRequestDestination
     */
    public $destination;

    /**
     * @var int
     */
    public $amount;

    /**
     * @var string value of Currency
     */
    public $currency;

    /**
     * @var string
     */
    public $reference;

    /**
     * @var PaymentSender
     */
    public $sender;

    /**
     * @var PaymentInstruction
     */
    public $instruction;

    /**
     * @var string
     */
    public $processing_channel_id;

    /**
     * The identifier of an existing payment from the recurring series.
     * [Optional]
     * @var string|null $previous_payment_id
     */
    public $previous_payment_id;

    /**
     * The order's line items.
     * [Optional]
     * @var CardPayoutItem[]|null $items
     */
    public $items;

    /**
     * The dimension details about business segment for payment request.
     * At least one dimension required.
     * [Optional]
     * @var PaymentSegment|null $segment
     */
    public $segment;

    /**
     * Processing information related to the payout.
     * [Optional]
     * @var CardPayoutProcessing|null $processing
     */
    public $processing;

    /**
     * Key-value pairs for custom metadata.
     * [Optional]
     * @var array|null $metadata
     */
    public $metadata;
}
