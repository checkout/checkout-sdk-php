<?php

namespace Checkout\Tests\Issuing\Disputes;

use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\Common\Currency;
use Checkout\Issuing\Disputes\Requests\CreateDisputeRequest;
use Checkout\Issuing\Disputes\Requests\EscalateDisputeRequest;
use Checkout\Issuing\Disputes\Requests\SubmitDisputeRequest;
use Checkout\Issuing\Testing\CardAuthorizationRequest;
use Checkout\Issuing\Testing\CardClearingAuthorizationRequest;
use Checkout\Issuing\Testing\CardSimulation;
use Checkout\Issuing\Testing\TransactionSimulation;
use Checkout\Issuing\Testing\TransactionType;
use Checkout\Tests\Issuing\AbstractIssuingIntegrationTest;

class DisputesIntegrationTest extends AbstractIssuingIntegrationTest
{
    private $cardholder;
    private $card;
    private $clearedTransactionId;

    /**
     * @before
     * @throws CheckoutAuthorizationException
     * @throws CheckoutArgumentException
     * @throws CheckoutException
     */
    public function beforeAll()
    {
        // Skip tests due to beta feature requiring special permissions
        $this->markTestSkipped("Requires permissions to create disputes and simulate transactions - we must ensure the test environment is set up correctly first");

        $this->before();
        $this->cardholder = $this->createCardholder();
        $this->card = $this->createCard($this->cardholder["id"], true);
        $this->clearedTransactionId = $this->createClearedTransaction();
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreateDispute()
    {
        $idempotencyKey = uniqid("create-dispute-");
        $request = $this->buildCreateDisputeRequest($this->clearedTransactionId);

        $response = $this->issuingApi->getIssuingClient()->createDispute($idempotencyKey, $request);

        $this->validateCreatedDispute($response, $request);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetDisputeDetails()
    {
        $idempotencyKey = uniqid("create-for-get-");
        $createRequest = $this->buildCreateDisputeRequest($this->clearedTransactionId);
        $createResponse = $this->issuingApi->getIssuingClient()->createDispute($idempotencyKey, $createRequest);

        $response = $this->issuingApi->getIssuingClient()->getDispute($createResponse["id"]);

        $this->validateDisputeDetails($response, $createResponse);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCancelDispute()
    {
        $idempotencyKey = uniqid("create-for-cancel-");
        $createRequest = $this->buildCreateDisputeRequest($this->clearedTransactionId);
        $createResponse = $this->issuingApi->getIssuingClient()->createDispute($idempotencyKey, $createRequest);

        $response = $this->issuingApi->getIssuingClient()->cancelDispute($createResponse["id"]);

        $this->assertNotNull($response);
        $this->assertTrue(is_array($response));

        // Verify the dispute was cancelled
        $updatedDispute = $this->issuingApi->getIssuingClient()->getDispute($createResponse["id"]);
        $this->assertEquals("canceled", $updatedDispute["status"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldEscalateDispute()
    {
        $idempotencyKey = uniqid("create-for-escalate-");
        $createRequest = $this->buildCreateDisputeRequest($this->clearedTransactionId);
        $createRequest->is_ready_for_submission = true; // Submit immediately to allow escalation
        $createResponse = $this->issuingApi->getIssuingClient()->createDispute($idempotencyKey, $createRequest);

        // Wait for dispute to be in a state that allows escalation
        // In practice, you might need to wait for the dispute status to change
        $escalateKey = uniqid("escalate-");
        $escalateRequest = $this->buildEscalateDisputeRequest();

        $response = $this->issuingApi->getIssuingClient()->escalateDispute($createResponse["id"], $escalateKey, $escalateRequest);

        $this->assertNotNull($response);
        $this->assertTrue(is_array($response));
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldSubmitDispute()
    {
        $idempotencyKey = uniqid("create-for-submit-");
        $createRequest = $this->buildCreateDisputeRequest($this->clearedTransactionId);
        $createRequest->is_ready_for_submission = false; // Create without submitting
        $createResponse = $this->issuingApi->getIssuingClient()->createDispute($idempotencyKey, $createRequest);

        $submitKey = uniqid("submit-");
        $submitRequest = $this->buildSubmitDisputeRequest();

        $response = $this->issuingApi->getIssuingClient()->submitDispute($createResponse["id"], $submitKey, $submitRequest);

        $this->validateSubmittedDispute($response, $createResponse["id"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldSubmitDisputeWithMinimalRequest()
    {
        $idempotencyKey = uniqid("create-for-submit-minimal-");
        $createRequest = $this->buildCreateDisputeRequest($this->clearedTransactionId);
        $createRequest->is_ready_for_submission = false; // Create without submitting
        $createResponse = $this->issuingApi->getIssuingClient()->createDispute($idempotencyKey, $createRequest);

        $submitKey = uniqid("submit-minimal-");
        $minimalRequest = new SubmitDisputeRequest(); // Minimal request with only required fields

        $response = $this->issuingApi->getIssuingClient()->submitDispute($createResponse["id"], $submitKey, $minimalRequest);

        $this->validateSubmittedDispute($response, $createResponse["id"]);
    }

    // Helper method to create a cleared transaction for dispute testing
    private function createClearedTransaction()
    {
        // Create an authorization
        $cardSimulation = new CardSimulation();
        $cardSimulation->id = $this->card["id"];
        $cardSimulation->expiry_month = $this->card["expiry_month"];
        $cardSimulation->expiry_year = $this->card["expiry_year"];

        $transactionSimulation = new TransactionSimulation();
        $transactionSimulation->type = TransactionType::$purchase;
        $transactionSimulation->amount = 1000; // $10.00 in cents
        $transactionSimulation->currency = Currency::$USD;

        $authRequest = new CardAuthorizationRequest();
        $authRequest->card = $cardSimulation;
        $authRequest->transaction = $transactionSimulation;

        $authResponse = $this->issuingApi->getIssuingClient()->simulateAuthorization($authRequest);

        // Clear the transaction to make it disputable
        $clearingRequest = new CardClearingAuthorizationRequest();
        $clearingRequest->amount = 1000; // Same amount as authorized

        $this->issuingApi->getIssuingClient()->simulateClearing($authResponse["id"], $clearingRequest);

        return $authResponse["id"];
    }

    // Setup Methods (Builders)
    private function buildCreateDisputeRequest($transactionId)
    {
        $request = new CreateDisputeRequest();
        $request->transaction_id = $transactionId;
        $request->reason = "4837"; // No cardholder authorization
        $request->evidence = [$this->buildEvidence("receipt.pdf", "Transaction receipt showing unauthorized charge")];
        $request->amount = 1000; // $10.00 in cents
        $request->is_ready_for_submission = false;
        $request->justification = "Customer reports unauthorized transaction";

        return $request;
    }

    private function buildEscalateDisputeRequest()
    {
        $request = new EscalateDisputeRequest();
        $request->justification = "Merchant response was insufficient. Escalating to pre-arbitration with additional evidence showing customer was in different location during transaction time.";
        $request->additional_evidence = [$this->buildEvidence("location_evidence.pdf", "GPS data showing customer location during transaction")];
        $request->amount = 800; // Reducing disputed amount to $8.00

        return $request;
    }

    private function buildSubmitDisputeRequest()
    {
        $request = new SubmitDisputeRequest();
        $request->reason = "4855"; // Goods or services not provided
        $request->evidence = [$this->buildEvidence("updated_receipt.pdf", "Updated receipt with corrected amount")];
        $request->amount = 750; // $7.50 in cents

        return $request;
    }

    private function buildEvidence($name, $description)
    {
        $evidence = new \stdClass();
        $evidence->name = $name;
        $evidence->content = base64_encode("Sample evidence content for " . $name);
        $evidence->description = $description;

        return $evidence;
    }

    // Validation Methods (Asserts)
    private function validateCreatedDispute($response, $request)
    {
        $this->assertResponse(
            $response,
            "id",
            "reason",
            "status",
            "transaction_id",
            "disputed_amount.amount",
            "disputed_amount.currency"
        );

        $this->assertStringStartsWith("idsp_", $response["id"]);
        $this->assertEquals($request->transaction_id, $response["transaction_id"]);
        $this->assertEquals($request->reason, $response["reason"]);
        $this->assertEquals("created", $response["status"]);

        if ($request->amount !== null) {
            $this->assertEquals($request->amount, $response["disputed_amount"]["amount"]);
        }
    }

    private function validateDisputeDetails($response, $originalResponse)
    {
        $this->assertResponse(
            $response,
            "id",
            "reason",
            "status",
            "transaction_id",
            "disputed_amount.amount",
            "disputed_amount.currency"
        );

        $this->assertEquals($originalResponse["id"], $response["id"]);
        $this->assertEquals($originalResponse["transaction_id"], $response["transaction_id"]);
        $this->assertEquals($originalResponse["reason"], $response["reason"]);
        $this->assertEquals($originalResponse["disputed_amount"]["amount"], $response["disputed_amount"]["amount"]);
        $this->assertEquals($originalResponse["disputed_amount"]["currency"], $response["disputed_amount"]["currency"]);
    }

    private function validateSubmittedDispute($response, $expectedDisputeId)
    {
        $this->assertResponse(
            $response,
            "id",
            "status"
        );

        $this->assertEquals($expectedDisputeId, $response["id"]);
        $this->assertContains($response["status"], ["processing", "action_required"]);
    }
}
