<?php

namespace Checkout\Tests\Issuing\Disputes;

use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\Issuing\IssuingClient;
use Checkout\Issuing\Disputes\Requests\CreateDisputeRequest;
use Checkout\Issuing\Disputes\Requests\EscalateDisputeRequest;
use Checkout\Issuing\Disputes\Requests\SubmitDisputeRequest;
use Checkout\PlatformType;
use Checkout\Tests\UnitTestFixture;

class DisputesClientTest extends UnitTestFixture
{
    /**
     * @var IssuingClient
     */
    private $client;

    /**
     * @before
     * @throws CheckoutAuthorizationException
     * @throws CheckoutArgumentException
     * @throws CheckoutException
     */
    public function init()
    {
        $this->initMocks(PlatformType::$default);
        $this->client = new IssuingClient($this->apiClient, $this->configuration);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreateDispute()
    {
        $this->apiClient
            ->method("post")
            ->willReturn($this->buildExpectedDisputeResponse());

        $request = $this->buildCreateDisputeRequest();
        $idempotencyKey = "test-idempotency-key";
        $response = $this->client->createDispute($idempotencyKey, $request);

        $this->validateDisputeResponse($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetDisputeDetails()
    {
        $disputeId = "idsp_fa6psq242dcd6fdn5gifcq1491";
        
        $this->apiClient
            ->method("get")
            ->willReturn($this->buildExpectedDisputeResponse($disputeId));

        $response = $this->client->getDispute($disputeId);

        $this->validateDisputeResponse($response, $disputeId);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCancelDispute()
    {
        $disputeId = "idsp_fa6psq242dcd6fdn5gifcq1491";
        
        $this->apiClient
            ->method("post")
            ->willReturn([]);

        $response = $this->client->cancelDispute($disputeId);

        $this->assertNotNull($response);
        $this->assertTrue(is_array($response));
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldEscalateDispute()
    {
        $disputeId = "idsp_fa6psq242dcd6fdn5gifcq1491";
        $idempotencyKey = "test-escalate-key";
        
        $this->apiClient
            ->method("post")
            ->willReturn([]);

        $request = $this->buildEscalateDisputeRequest();
        $response = $this->client->escalateDispute($disputeId, $idempotencyKey, $request);

        $this->assertNotNull($response);
        $this->assertTrue(is_array($response));
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldSubmitDispute()
    {
        $disputeId = "idsp_fa6psq242dcd6fdn5gifcq1491";
        $idempotencyKey = "test-submit-key";
        
        $this->apiClient
            ->method("post")
            ->willReturn($this->buildExpectedDisputeResponse($disputeId));

        $request = $this->buildSubmitDisputeRequest();
        $response = $this->client->submitDispute($disputeId, $idempotencyKey, $request);

        $this->validateDisputeResponse($response, $disputeId);
    }

    // Setup Methods (Builders)

    private function buildCreateDisputeRequest()
    {
        $request = new CreateDisputeRequest();
        $request->transaction_id = "trx_aayhhfwbdyxwcaeyhhfwbd4xga";
        $request->reason = "4837";
        $request->evidence = [$this->buildEvidence()];
        $request->amount = 1000;
        $request->presentment_message_id = "msg_fa6psq242dcd6fdn5gifcq1491";
        $request->is_ready_for_submission = false;
        $request->justification = "Customer dispute";

        return $request;
    }

    private function buildEscalateDisputeRequest()
    {
        $request = new EscalateDisputeRequest();
        $request->justification = "Escalating due to additional evidence";
        $request->additional_evidence = [$this->buildEvidence("additional_evidence.pdf", "Additional supporting documentation")];
        $request->amount = 500;

        return $request;
    }

    private function buildSubmitDisputeRequest()
    {
        $request = new SubmitDisputeRequest();
        $request->reason = "4855";
        $request->evidence = [$this->buildEvidence("updated_evidence.pdf", "Updated evidence file")];
        $request->amount = 750;

        return $request;
    }

    private function buildEvidence($name = "receipt.pdf", $description = "Transaction receipt")
    {
        $evidence = new \stdClass();
        $evidence->name = $name;
        $evidence->content = "SGVsbG8gV29ybGQ="; // Base64 encoded "Hello World"
        $evidence->description = $description;

        return $evidence;
    }

    private function buildExpectedDisputeResponse($disputeId = "idsp_fa6psq242dcd6fdn5gifcq1491")
    {
        return [
            "id" => $disputeId,
            "reason" => "4837",
            "disputed_amount" => [
                "amount" => 1000,
                "currency" => "USD"
            ],
            "status" => "created",
            "status_reason" => "chargeback_pending",
            "transaction_id" => "trx_aayhhfwbdyxwcaeyhhfwbd4xga",
            "presentment_message_id" => "msg_fa6psq242dcd6fdn5gifcq1491"
        ];
    }

    // Validation Methods (Asserts)

    private function validateDisputeResponse($response, $expectedId = "idsp_fa6psq242dcd6fdn5gifcq1491")
    {
        $this->assertNotNull($response);
        $this->assertTrue(is_array($response));
        $this->assertArrayHasKey("id", $response);
        $this->assertArrayHasKey("reason", $response);
        $this->assertArrayHasKey("status", $response);
        $this->assertArrayHasKey("transaction_id", $response);
        $this->assertArrayHasKey("disputed_amount", $response);

        $this->assertEquals($expectedId, $response["id"]);
        $this->assertEquals("4837", $response["reason"]);
        $this->assertEquals("created", $response["status"]);
        $this->assertEquals("trx_aayhhfwbdyxwcaeyhhfwbd4xga", $response["transaction_id"]);

        // Validate disputed amount structure
        $this->assertTrue(is_array($response["disputed_amount"]));
        $this->assertArrayHasKey("amount", $response["disputed_amount"]);
        $this->assertArrayHasKey("currency", $response["disputed_amount"]);
        $this->assertEquals(1000, $response["disputed_amount"]["amount"]);
        $this->assertEquals("USD", $response["disputed_amount"]["currency"]);
    }
}
