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
    public function shouldQueryDisputes()
    {
        $disputesQueryFilter = new DisputesQueryFilter();
        $disputesQueryFilter->limit = 100;

        $from = new DateTime();
        $from->setTimezone(new DateTimeZone("europe/madrid"));
        $from->sub(new DateInterval("P1Y"));

        $disputesQueryFilter->from = $from;
        $disputesQueryFilter->to = new DateTime(); // UTC, now

        $response = $this->checkoutApi->getDisputesClient()->query($disputesQueryFilter);
        $this->assertResponse(
            $response,
            "limit",
            "from",
            "to"
        );
        if (array_key_exists("data", $response) && sizeof($response["data"]) > 0) {
            $disputeDetails = $response["data"]["0"];
            $this->assertResponse(
                $disputeDetails,
                "id",
                "category",
                "status",
                "amount",
                "currency",
                "reason_code",
                "payment_id"
            );
        }
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldUploadFile()
    {
        $fileRequest = new FileRequest();
        $fileRequest->file = $this->getCheckoutFilePath();
        $fileRequest->purpose = "dispute_evidence";

        $uploadFileResponse = $this->checkoutApi->getDisputesClient()->uploadFile($fileRequest);
        $this->assertResponse($uploadFileResponse, "id");

        $fileDetails = $this->checkoutApi->getDisputesClient()->getFileDetails($uploadFileResponse["id"]);
        $this->assertResponse(
            $fileDetails,
            "id",
            "filename",
            "purpose",
            "size",
            "uploaded_on",
            "_links"
        );
        $this->assertEquals($fileRequest->purpose, $fileDetails["purpose"]);
        $this->assertTrue(strpos($fileRequest->file, $fileDetails["filename"]) !== false);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetDisputeSchemeFiles()
    {
        $disputesQueryFilter = new DisputesQueryFilter();
        $disputesQueryFilter->limit = 5;

        $queryResponse = $this->checkoutApi->getDisputesClient()->query($disputesQueryFilter);
        if (array_key_exists("data", $queryResponse)) {
            foreach ($queryResponse["data"] as $dispute) {
                $schemeFiles = $this->checkoutApi->getDisputesClient()->getDisputeSchemeFiles($dispute["id"]);
                $this->assertResponse($schemeFiles, "id", "files");
            }
        }
    }

    /**
     * Disabled due the time that takes to finish, run on demand
     * @throws CheckoutApiException
     */
    public function shouldTestFullDisputesWorkflow()
    {
        $payment = $this->makeCardPayment(true, 1040);

        $filter = new DisputesQueryFilter();
        $filter->payment_id = $payment["id"];

        $queryResponse = $this->retriable(
            function () use (&$filter) {
                return $this->checkoutApi->getDisputesClient()->query($filter);
            },
            $this->thereAreDisputes()
        );

        $this->assertResponse($queryResponse, "data");
        $this->assertEquals($payment["id"], $queryResponse["data"]["0"]["payment_id"]);

        $fileRequest = new FileRequest();
        $fileRequest->file = $this->getCheckoutFilePath();
        $fileRequest->purpose = "dispute_evidence";

        $uploadFileResponse = $this->checkoutApi->getDisputesClient()->uploadFile($fileRequest);
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

        $updateResponse = $this->checkoutApi->getDisputesClient()->putEvidence($disputeId, $disputeEvidenceRequest);
        self::assertArrayHasKey("http_metadata", $updateResponse);
        self::assertEquals(204, $updateResponse["http_metadata"]->getStatusCode());

        //Get compiled submitted evidence
        $compiledSubmittedEvidenceResponse
            = $this->checkoutApi->getDisputesClient()->getCompiledSubmittedEvidence($disputeId);
        $this->assertResponse(
            $compiledSubmittedEvidenceResponse,
            "file_id",
            "_links",
            "_links.self"
        );


        $evidence = $this->checkoutApi->getDisputesClient()->getEvidence($disputeId);
        $this->assertResponse(
            $evidence,
            "proof_of_delivery_or_service_file",
            "proof_of_delivery_or_service_text",
            "invoice_or_receipt_file",
            "invoice_or_receipt_text",
            "customer_communication_file",
            "customer_communication_text",
            "additional_evidence_file",
            "additional_evidence_text"
        );
    }

    /**
     * @return Closure
     */
    private function thereAreDisputes()
    {
        return function ($response) {
            return array_key_exists("total_count", $response) && $response["total_count"] != 0;
        };
    }
}
