<?php

namespace Checkout\Tests\Issuing\DigitalCards;

use Checkout\CheckoutApiException;
use Checkout\Tests\Issuing\AbstractIssuingIntegrationTest;

class DigitalCardsIntegrationTest extends AbstractIssuingIntegrationTest
{
    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetDigitalCard()
    {
        $this->markTestSkipped("requires a valid digital card ID from a provisioned card");

        $digitalCardId = "dcr_5ngxzsynm2me3oxf73esbhda6q"; // Replace with actual digital card ID

        $response = $this->issuingApi->getIssuingClient()->getDigitalCard($digitalCardId);

        $this->validateGetDigitalCardResponse($response, $digitalCardId);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetDigitalCardWithFullDetails()
    {
        $this->markTestSkipped("requires a valid digital card ID with complete provisioning details");

        $digitalCardId = "dcr_example_full_digital_card_id"; // Replace with actual digital card ID

        $response = $this->issuingApi->getIssuingClient()->getDigitalCard($digitalCardId);

        $this->validateCompleteDigitalCardResponse($response, $digitalCardId);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCompleteDigitalCardWorkflow()
    {
        $this->markTestSkipped("requires card provisioning workflow setup and valid digital card IDs");

        // Note: This would be a complete workflow test that:
        // 1. Uses an existing card with digital provisioning
        // 2. Retrieves the digital card details
        // 3. Verifies all expected properties are present
        // 4. Validates the digital card status and metadata

        // For now, this serves as documentation of the expected flow
    }

    private function validateGetDigitalCardResponse(array $response, string $expectedDigitalCardId): void
    {
        $this->assertResponse($response, "id", "last_four", "status", "type", "cardholder_id", "card_id");
        
        $this->assertEquals($expectedDigitalCardId, $response["id"]);
        $this->assertNotEmpty($response["last_four"]);
        $this->assertTrue(in_array($response["status"], ["active", "suspended", "inactive"]));
        $this->assertTrue(in_array($response["type"], ["secure_element", "hce"]));
        
        // Validate cardholder_id and card_id patterns
        $this->assertTrue(strpos($response["cardholder_id"], "crh_") === 0);
        $this->assertTrue(strpos($response["card_id"], "crd_") === 0);
    }

    private function validateCompleteDigitalCardResponse(array $response, string $expectedDigitalCardId): void
    {
        $this->validateGetDigitalCardResponse($response, $expectedDigitalCardId);
        
        // Additional validations for complete response
        if (isset($response["bin"])) {
            $this->assertNotEmpty($response["bin"]);
        }
        
        if (isset($response["scheme"])) {
            $this->assertTrue(in_array($response["scheme"], ["visa", "mastercard", "amex", "discover"]));
        }
        
        if (isset($response["created_time"])) {
            $this->assertNotEmpty($response["created_time"]);
        }
        
        if (isset($response["display_name"])) {
            $this->assertNotEmpty($response["display_name"]);
        }
        
        if (isset($response["expiry_month"])) {
            $this->assertTrue(is_int($response["expiry_month"]));
            $this->assertTrue($response["expiry_month"] >= 1 && $response["expiry_month"] <= 12);
        }
        
        if (isset($response["expiry_year"])) {
            $this->assertTrue(is_int($response["expiry_year"]));
            $this->assertTrue($response["expiry_year"] >= date("Y"));
        }

        // Validate nested device object
        if (isset($response["device"])) {
            $this->assertTrue(is_array($response["device"]));
            if (isset($response["device"]["type"])) {
                $this->assertNotEmpty($response["device"]["type"]);
            }
        }

        // Validate nested requestor object
        if (isset($response["requestor"])) {
            $this->assertTrue(is_array($response["requestor"]));
            if (isset($response["requestor"]["name"])) {
                $this->assertNotEmpty($response["requestor"]["name"]);
            }
        }

        // Validate metadata
        if (isset($response["metadata"])) {
            $this->assertTrue(is_array($response["metadata"]));
        }
    }
}
