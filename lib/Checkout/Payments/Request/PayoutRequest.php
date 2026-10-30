<?php

namespace Checkout\Payments\Request;

use Checkout\Payments\Destination\PaymentRequestDestination;
use Checkout\Payments\Request\Source\PayoutRequestSource;
use Checkout\Payments\Sender\PaymentSender;

use Checkout\Payments\Request\CardPayoutItem;
use Checkout\Payments\Request\CardPayoutProcessing;
use Checkout\Payments\Request\PaymentSegment;

/**
 * A card payout or bank payout request, sent to POST /payments.
 */
class PayoutRequest
{

    /**
     * The source of the payout.
     * [Required]
     * @var PayoutRequestSource
     */
    public $source;

    /**
     * The destination of the payout.
     * You can encrypt this field by using the Cko-Encryption-Key-Id and Cko-Encrypted-Properties headers.
     * [Required]
     * Card payout destinations: PaymentRequestCardDestination, PaymentRequestNetworkTokenDestination,
     * PaymentRequestIdDestination, PaymentRequestTokenDestination.
     * Bank payout destinations: PaymentRequestIdDestination, PaymentBankAccountDestination.
     * All in the Checkout\Payments\Destination namespace.
     * @var PaymentRequestDestination
     */
    public $destination;

    /**
     * The amount to pay out, in minor units.
     * [Optional] for card payouts, [Required] for bank payouts
     * min 0
     * @var int
     */
    public $amount;

    /**
     * The three-letter ISO 4217 code for the currency you want to pay out to the recipient.
     * For bank payouts, the currency should match that of the destination account.
     * [Required]
     * min 3 characters and max 3 characters for bank payouts
     * @var string value of Currency
     */
    public $currency;

    /**
     * A custom reference to identify the payout. For example, an order number.
     * [Optional]
     * max 50 characters for card payouts
     * @var string
     */
    public $reference;

    /**
     * The billing descriptor for bank payouts. Beneficiary banks typically construct it using the
     * billing descriptor reference and the sender information.
     * [Required] for bank payouts, not used by card payouts
     * @var PayoutBillingDescriptor
     */
    public $billing_descriptor;

    /**
     * The sender of the payout.
     * Required for money transfer card payouts. For bank payouts, the ultimate sender of the payout,
     * that is, your customer; not supported for Platform or Direct Seller Checkout.com clients.
     * [Optional]
     * @var PaymentSender
     */
    public $sender;

    /**
     * Additional details about the payout instruction.
     * [Required] for card payouts, [Optional] for bank payouts
     * @var PaymentInstruction
     */
    public $instruction;

    /**
     * The unique identifier for the processing channel.
     * [Required]
     * ^(pc)_(\w{26})$
     * min 29 characters and max 29 characters for card payouts
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
