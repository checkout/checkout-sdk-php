<?php

namespace Checkout\Disputes;

class DisputeEvidenceRequest
{
    public $proof_of_delivery_or_service_file;

    public $proof_of_delivery_or_service_text;

    public $invoice_or_receipt_file;

    public $invoice_or_receipt_text;

    public $invoice_showing_distinct_transactions_file;

    public $invoice_showing_distinct_transactions_text;

    public $customer_communication_file;

    public $customer_communication_text;

    public $refund_or_cancellation_policy_file;

    public $refund_or_cancellation_policy_text;

    public $recurring_transaction_agreement_file;

    public $recurring_transaction_agreement_text;

    public $additional_evidence_file;

    public $additional_evidence_text;

    public $proof_of_delivery_or_service_date_file;

    public $proof_of_delivery_or_service_date_text;
}
