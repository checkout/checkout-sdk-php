<?php

namespace Checkout\Tests\Disputes;

use Checkout\CheckoutApiException;
use Checkout\Disputes\DisputeEvidenceRequest;
use Checkout\Disputes\DisputesQueryFilter;
use Checkout\Files\FileRequest;
use Checkout\Tests\Payments\AbstractPaymentsIntegrationTest;
use Closure;
use DateInterval;
use DateTime;
use DateTimeZone;

class DisputesIntegrationTest extends AbstractPaymentsIntegrationTest
{
    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldQueryDisputes(): void
    {
        $disputesQueryFilter = new DisputesQueryFilter();

        $from = new DateTime();
        $from->setTimezone(new DateTimeZone("America/Mexico_City"));
        $from->sub(new DateInterval("P1Y"));

        $disputesQueryFilter->from = $from;
        $disputesQueryFilter->to = new DateTime(); // UTC, now

        $response = $this->defaultApi->getDisputesClient()->query($disputesQueryFilter);
        $this->assertResponse($response,
            "limit",
            "total_count",
            "from",
            "to");
        if (array_key_exists("data", $response)) {
            $disputeDetails = $response["data"]["0"];
            $this->assertResponse($disputeDetails,
                "id",
                "category",
                "status",
                "amount",
                "currency",
                "reason_code",
                "payment_id");

        }
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldUploadFile(): void
    {
        $fileRequest = new FileRequest();
        $fileRequest->file = self::getCheckoutFilePath();
        $fileRequest->purpose = "dispute_evidence";

        $uploadFileResponse = $this->defaultApi->getDisputesClient()->uploadFile($fileRequest);
        $this->assertResponse($uploadFileResponse, "id");

        $fileDetails = $this->defaultApi->getDisputesClient()->getFileDetails($uploadFileResponse["id"]);
        $this->assertResponse($fileDetails, "id",
            "filename",
            "purpose",
            "size",
            "uploaded_on",
            "_links");
        self::assertEquals($fileRequest->purpose, $fileDetails["purpose"]);
        self::assertTrue(strpos($fileRequest->file, $fileDetails["filename"]) !== false);
    }

    /**
     * Disabled due the time that takes to finish, run on demand
     * @throws CheckoutApiException
     */
    public function shouldTestFullDisputesWorkflow(): void
    {
        $payment = $this->makeCardPayment(true, 1040);

        $filter = new DisputesQueryFilter();
        $filter->payment_id = $payment["id"];

        $queryResponse = self::retriable(fn() => $this->defaultApi->getDisputesClient()->query($filter), $this->thereAreDisputes());

        $this->assertResponse($queryResponse, "data");
        self::assertEquals($payment["id"], $queryResponse["data"]["0"]["payment_id"]);

        $fileRequest = new FileRequest();
        $fileRequest->file = self::getCheckoutFilePath();
        $fileRequest->purpose = "dispute_evidence";

        $uploadFileResponse = $this->defaultApi->getDisputesClient()->uploadFile($fileRequest);
        $this->assertResponse($uploadFileResponse, "id");

        $disputeEvidenceRequest = new DisputeEvidenceRequest();
        $disputeEvidenceRequest->proof_of_delivery_or_service_file = $uploadFileResponse["id"];
        $disputeEvidenceRequest->proof_of_delivery_or_service_text = "proof of delivery or service text";
        $disputeEvidenceRequest->invoice_or_receipt_file = $uploadFileResponse["id"];
        $disputeEvidenceRequest->invoice_or_receipt_text = "Copy of the invoice";
        $disputeEvidenceRequest->customer_communication_file = $uploadFileResponse["id"];
        $disputeEvidenceRequest->customer_communication_text = "Copy of an email exchange with the cardholder";
        $disputeEvidenceRequest->additional_evidence_file = $uploadFileResponse["id"];
        $disputeEvidenceRequest->additional_evidence_text = "Scanned document";

        $disputeId = $queryResponse["data"]["0"]["id"];

        $this->defaultApi->getDisputesClient()->putEvidence($disputeId, $disputeEvidenceRequest);

        $evidence = $this->defaultApi->getDisputesClient()->getEvidence($disputeId);
        $this->assertResponse($evidence,
            "proof_of_delivery_or_service_file",
            "proof_of_delivery_or_service_text",
            "invoice_or_receipt_file",
            "invoice_or_receipt_text",
            "customer_communication_file",
            "customer_communication_text",
            "additional_evidence_file",
            "additional_evidence_text");

    }

    /**
     * @return Closure
     */
    private function thereAreDisputes(): Closure
    {
        return fn($response) => array_key_exists("total_count", $response) && $response["total_count"] != 0;
    }

}
