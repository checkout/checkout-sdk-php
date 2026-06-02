<?php

namespace Checkout\Disputes;

use Checkout\Disputes\CompellingEvidence;

class DisputeEvidenceRequest
{
    /**
     * @var string
     */
    public $proof_of_delivery_or_service_file;

    /**
     * @var string
     */
    public $proof_of_delivery_or_service_text;

    /**
     * @var string
     */
    public $invoice_or_receipt_file;

    /**
     * @var string
     */
    public $invoice_or_receipt_text;

    /**
     * @var string
     */
    public $invoice_showing_distinct_transactions_file;

    /**
     * @var string
     */
    public $invoice_showing_distinct_transactions_text;

    /**
     * @var string
     */
    public $customer_communication_file;

    /**
     * @var string
     */
    public $customer_communication_text;

    /**
     * @var string
     */
    public $refund_or_cancellation_policy_file;

    /**
     * @var string
     */
    public $refund_or_cancellation_policy_text;

    /**
     * @var string
     */
    public $recurring_transaction_agreement_file;

    /**
     * @var string
     */
    public $recurring_transaction_agreement_text;

    /**
     * @var string
     */
    public $additional_evidence_file;

    /**
     * @var string
     */
    public $additional_evidence_text;

    /**
     * @var string
     */
    public $proof_of_delivery_or_service_date_file;

    /**
     * @var string
     */
    public $proof_of_delivery_or_service_date_text;

    /**
     * Supporting evidence files for arbitration where no review is requested.
     * [Optional]
     * @var string|null $arbitration_no_review_files
     */
    public $arbitration_no_review_files;

    /**
     * Supporting evidence text for arbitration where no review is requested.
     * [Optional]
     * @var string|null $arbitration_no_review_text
     */
    public $arbitration_no_review_text;

    /**
     * Supporting evidence files for arbitration where a review is required.
     * [Optional]
     * @var string|null $arbitration_review_required_files
     */
    public $arbitration_review_required_files;

    /**
     * Supporting evidence text for arbitration where a review is required.
     * [Optional]
     * @var string|null $arbitration_review_required_text
     */
    public $arbitration_review_required_text;

    /**
     * Compelling evidence for the dispute.
     * [Optional]
     * @var CompellingEvidence|null $compelling_evidence
     */
    public $compelling_evidence;
}
