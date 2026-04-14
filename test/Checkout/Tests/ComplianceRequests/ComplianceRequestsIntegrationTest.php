<?php

namespace Checkout\Tests\ComplianceRequests;

use Checkout\CheckoutApiException;
use Checkout\ComplianceRequests\Entities\ComplianceRespondedField;
use Checkout\ComplianceRequests\Entities\ComplianceRespondedFields;
use Checkout\ComplianceRequests\Requests\ComplianceRequestRespondRequest;
use Checkout\PlatformType;
use Checkout\Tests\SandboxTestFixture;

class ComplianceRequestsIntegrationTest extends SandboxTestFixture
{
    /**
     * @before
     */
    public function before()
    {
        $this->init(PlatformType::$default_oauth);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetComplianceRequest()
    {
        $this->markTestSkipped("requires an existing compliance request with provided payment ID");

        $paymentId = "pay_example123456789"; // Replace with actual payment ID that has compliance request

        $response = $this->checkoutApi->getComplianceRequestsClient()->getComplianceRequest($paymentId);

        $this->validateGetComplianceRequestResponse($response, $paymentId);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldRespondToComplianceRequest()
    {
        $this->markTestSkipped("requires an existing pending compliance request");

        $paymentId = "pay_example123456789"; // Replace with actual payment ID that has pending compliance request
        $request = $this->buildValidRespondRequest();

        $response = $this->checkoutApi->getComplianceRequestsClient()->respondToComplianceRequest($paymentId, $request);

        $this->validateRespondToComplianceRequestResponse($response, $paymentId);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldRespondToComplianceRequestWithSenderOnly()
    {
        $this->markTestSkipped("requires an existing pending compliance request that needs sender information");

        $paymentId = "pay_example123456789";
        $request = $this->buildRespondRequestWithSenderFields();

        $response = $this->checkoutApi->getComplianceRequestsClient()->respondToComplianceRequest($paymentId, $request);

        $this->validateRespondToComplianceRequestResponse($response, $paymentId);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldRespondToComplianceRequestWithRecipientOnly()
    {
        $this->markTestSkipped("requires an existing pending compliance request that needs recipient information");

        $paymentId = "pay_example123456789";
        $request = $this->buildRespondRequestWithRecipientFields();

        $response = $this->checkoutApi->getComplianceRequestsClient()->respondToComplianceRequest($paymentId, $request);

        $this->validateRespondToComplianceRequestResponse($response, $paymentId);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldRespondToComplianceRequestWithComments()
    {
        $this->markTestSkipped("requires an existing pending compliance request");

        $paymentId = "pay_example123456789";
        $request = $this->buildRespondRequestWithComments();

        $response = $this->checkoutApi->getComplianceRequestsClient()->respondToComplianceRequest($paymentId, $request);

        $this->validateRespondToComplianceRequestResponse($response, $paymentId);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCompleteComplianceRequestWorkflow()
    {
        $this->markTestSkipped("requires setup of payment that triggers compliance request");

        // Note: This would be a complete workflow test that:
        // 1. Creates a payment that triggers a compliance request
        // 2. Retrieves the compliance request
        // 3. Responds to the compliance request
        // 4. Verifies the status changes appropriately

        // For now, this serves as documentation of the expected flow
    }

    // Common builder methods for DRY setup
    private function buildValidRespondRequest(): ComplianceRequestRespondRequest
    {
        $senderField = new ComplianceRespondedField();
        $senderField->name = "sender_name";
        $senderField->value = "John Doe";
        $senderField->not_available = false;

        $recipientField = new ComplianceRespondedField();
        $recipientField->name = "recipient_name";
        $recipientField->value = "Jane Smith";
        $recipientField->not_available = false;

        $fields = new ComplianceRespondedFields();
        $fields->sender = [$senderField];
        $fields->recipient = [$recipientField];

        $request = new ComplianceRequestRespondRequest();
        $request->fields = $fields;

        return $request;
    }

    private function buildRespondRequestWithSenderFields(): ComplianceRequestRespondRequest
    {
        $senderField1 = new ComplianceRespondedField();
        $senderField1->name = "sender_full_name";
        $senderField1->value = "John Michael Doe";
        $senderField1->not_available = false;

        $senderField2 = new ComplianceRespondedField();
        $senderField2->name = "sender_address";
        $senderField2->value = "123 Main Street, London, UK";
        $senderField2->not_available = false;

        $senderField3 = new ComplianceRespondedField();
        $senderField3->name = "sender_phone";
        $senderField3->value = null;
        $senderField3->not_available = true; // Not available

        $fields = new ComplianceRespondedFields();
        $fields->sender = [$senderField1, $senderField2, $senderField3];
        $fields->recipient = null;

        $request = new ComplianceRequestRespondRequest();
        $request->fields = $fields;

        return $request;
    }

    private function buildRespondRequestWithRecipientFields(): ComplianceRequestRespondRequest
    {
        $recipientField1 = new ComplianceRespondedField();
        $recipientField1->name = "recipient_account_number";
        $recipientField1->value = "GB29 NWBK 6016 1331 9268 19";
        $recipientField1->not_available = false;

        $recipientField2 = new ComplianceRespondedField();
        $recipientField2->name = "recipient_institution";
        $recipientField2->value = "NatWest Bank";
        $recipientField2->not_available = false;

        $fields = new ComplianceRespondedFields();
        $fields->sender = null;
        $fields->recipient = [$recipientField1, $recipientField2];

        $request = new ComplianceRequestRespondRequest();
        $request->fields = $fields;

        return $request;
    }

    private function buildRespondRequestWithComments(): ComplianceRequestRespondRequest
    {
        $request = $this->buildValidRespondRequest();
        $request->comments = "This transaction is a legitimate business payment for consulting services. " .
                           "The sender and recipient have an established business relationship.";
        return $request;
    }

    private function validateGetComplianceRequestResponse(array $response, string $expectedPaymentId): void
    {
        $this->assertResponse($response, "id", "payment_id", "status", "created_on");
        
        $this->assertEquals($expectedPaymentId, $response["payment_id"]);
        $this->assertNotEmpty($response["id"]);
        $this->assertTrue(in_array($response["status"], ["pending", "submitted", "completed"]));
        
        if (isset($response["requested_fields"])) {
            $this->assertTrue(is_array($response["requested_fields"]));
        }
        
        if (isset($response["reason"])) {
            $this->assertNotEmpty($response["reason"]);
        }
    }

    private function validateRespondToComplianceRequestResponse(array $response, string $expectedPaymentId): void
    {
        $this->assertResponse($response, "id", "payment_id", "status", "updated_on");
        
        $this->assertEquals($expectedPaymentId, $response["payment_id"]);
        $this->assertNotEmpty($response["id"]);
        $this->assertTrue(in_array($response["status"], ["submitted", "completed"]));
        $this->assertNotEmpty($response["updated_on"]);
    }
}
