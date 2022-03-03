<?php

namespace Checkout\Disputes;

class DisputeEvidenceRequest
{
    public string $proof_of_delivery_or_service_file;

    public string $proof_of_delivery_or_service_text;

    public string $invoice_or_receipt_file;

    public string $invoice_or_receipt_text;

    public string $invoice_showing_distinct_transactions_file;

    public string $invoice_showing_distinct_transactions_text;

    public string $customer_communication_file;

    public string $customer_communication_text;

    public string $refund_or_cancellation_policy_file;

    public string $refund_or_cancellation_policy_text;

    public string $recurring_transaction_agreement_file;

    public string $recurring_transaction_agreement_text;

    public string $additional_evidence_file;

    public string $additional_evidence_text;

    public string $proof_of_delivery_or_service_date_file;

    public string $proof_of_delivery_or_service_date_text;
}
