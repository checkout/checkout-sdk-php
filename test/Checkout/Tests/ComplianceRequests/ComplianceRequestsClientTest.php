<?php

namespace Checkout\Tests\ComplianceRequests;

use Checkout\CheckoutApiException;
use Checkout\ComplianceRequests\ComplianceRequestsClient;
use Checkout\ComplianceRequests\Entities\ComplianceRespondedField;
use Checkout\ComplianceRequests\Entities\ComplianceRespondedFields;
use Checkout\ComplianceRequests\Requests\ComplianceRequestRespondRequest;
use Checkout\PlatformType;
use Checkout\Tests\UnitTestFixture;

class ComplianceRequestsClientTest extends UnitTestFixture
{
    /**
     * @var ComplianceRequestsClient
     */
    private $client;

    /**
     * @before
     */
    public function init()
    {
        $this->initMocks(PlatformType::$default_oauth);
        $this->client = new ComplianceRequestsClient($this->apiClient, $this->configuration);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetComplianceRequest()
    {
        $paymentId = "pay_y3oqhf46pyzuxjbcn2giaqnb44";

        $this->apiClient
            ->method("get")
            ->with(
                $this->equalTo("compliance-requests/" . $paymentId),
                $this->anything()
            )
            ->willReturn($this->buildGetComplianceRequestResponse());

        $response = $this->client->getComplianceRequest($paymentId);

        $this->validateGetComplianceRequestResponse($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldRespondToComplianceRequest()
    {
        $paymentId = "pay_y3oqhf46pyzuxjbcn2giaqnb44";
        $request = $this->buildValidRespondRequest();

        $this->apiClient
            ->method("post")
            ->with(
                $this->equalTo("compliance-requests/" . $paymentId),
                $this->equalTo($request),
                $this->anything()
            )
            ->willReturn($this->buildRespondToComplianceRequestResponse());

        $response = $this->client->respondToComplianceRequest($paymentId, $request);

        $this->validateRespondToComplianceRequestResponse($response);
    }



    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldRespondToComplianceRequestWithSenderFields()
    {
        $paymentId = "pay_y3oqhf46pyzuxjbcn2giaqnb44";
        $request = $this->buildRespondRequestWithSenderFields();

        $this->apiClient
            ->method("post")
            ->with(
                $this->equalTo("compliance-requests/" . $paymentId),
                $this->equalTo($request),
                $this->anything()
            )
            ->willReturn($this->buildRespondToComplianceRequestResponse());

        $response = $this->client->respondToComplianceRequest($paymentId, $request);

        $this->validateRespondToComplianceRequestResponse($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldRespondToComplianceRequestWithRecipientFields()
    {
        $paymentId = "pay_y3oqhf46pyzuxjbcn2giaqnb44";
        $request = $this->buildRespondRequestWithRecipientFields();

        $this->apiClient
            ->method("post")
            ->with(
                $this->equalTo("compliance-requests/" . $paymentId),
                $this->equalTo($request),
                $this->anything()
            )
            ->willReturn($this->buildRespondToComplianceRequestResponse());

        $response = $this->client->respondToComplianceRequest($paymentId, $request);

        $this->validateRespondToComplianceRequestResponse($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldRespondToComplianceRequestWithComments()
    {
        $paymentId = "pay_y3oqhf46pyzuxjbcn2giaqnb44";
        $request = $this->buildRespondRequestWithComments();

        $this->apiClient
            ->method("post")
            ->with(
                $this->equalTo("compliance-requests/" . $paymentId),
                $this->equalTo($request),
                $this->anything()
            )
            ->willReturn($this->buildRespondToComplianceRequestResponse());

        $response = $this->client->respondToComplianceRequest($paymentId, $request);

        $this->validateRespondToComplianceRequestResponse($response);
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
        $senderField1->name = "sender_name";
        $senderField1->value = "John Doe";
        $senderField1->not_available = false;

        $senderField2 = new ComplianceRespondedField();
        $senderField2->name = "sender_address";
        $senderField2->value = null;
        $senderField2->not_available = true;

        $fields = new ComplianceRespondedFields();
        $fields->sender = [$senderField1, $senderField2];
        $fields->recipient = null;

        $request = new ComplianceRequestRespondRequest();
        $request->fields = $fields;

        return $request;
    }

    private function buildRespondRequestWithRecipientFields(): ComplianceRequestRespondRequest
    {
        $recipientField = new ComplianceRespondedField();
        $recipientField->name = "recipient_account";
        $recipientField->value = "12345678";
        $recipientField->not_available = false;

        $fields = new ComplianceRespondedFields();
        $fields->sender = null;
        $fields->recipient = [$recipientField];

        $request = new ComplianceRequestRespondRequest();
        $request->fields = $fields;

        return $request;
    }

    private function buildRespondRequestWithComments(): ComplianceRequestRespondRequest
    {
        $request = $this->buildValidRespondRequest();
        $request->comments = "This is additional information for compliance review";
        return $request;
    }

    private function buildGetComplianceRequestResponse(): array
    {
        return [
            "id" => "cmpr_01hxh9f3p5g2x6kj8nm4cd7v9y",
            "payment_id" => "pay_y3oqhf46pyzuxjbcn2giaqnb44",
            "status" => "pending",
            "created_on" => "2024-03-11T10:30:00Z",
            "requested_fields" => [
                "sender" => ["sender_name", "sender_address"],
                "recipient" => ["recipient_name", "recipient_account"]
            ],
            "reason" => "regulatory_compliance"
        ];
    }

    private function buildRespondToComplianceRequestResponse(): array
    {
        return [
            "id" => "cmpr_01hxh9f3p5g2x6kj8nm4cd7v9y",
            "payment_id" => "pay_y3oqhf46pyzuxjbcn2giaqnb44",
            "status" => "submitted",
            "updated_on" => "2024-03-11T10:35:00Z"
        ];
    }

    private function validateGetComplianceRequestResponse(array $response): void
    {
        $this->assertNotNull($response);
        $this->assertArrayHasKey("id", $response);
        $this->assertArrayHasKey("payment_id", $response);
        $this->assertArrayHasKey("status", $response);
        $this->assertArrayHasKey("created_on", $response);
        $this->assertEquals("cmpr_01hxh9f3p5g2x6kj8nm4cd7v9y", $response["id"]);
        $this->assertEquals("pay_y3oqhf46pyzuxjbcn2giaqnb44", $response["payment_id"]);
    }

    private function validateRespondToComplianceRequestResponse(array $response): void
    {
        $this->assertNotNull($response);
        $this->assertArrayHasKey("id", $response);
        $this->assertArrayHasKey("payment_id", $response);
        $this->assertArrayHasKey("status", $response);
        $this->assertArrayHasKey("updated_on", $response);
        $this->assertEquals("cmpr_01hxh9f3p5g2x6kj8nm4cd7v9y", $response["id"]);
        $this->assertEquals("submitted", $response["status"]);
    }
}
