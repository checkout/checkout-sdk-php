<?php

namespace Checkout\Tests\Issuing\DigitalCards;

use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\Issuing\IssuingClient;
use Checkout\PlatformType;
use Checkout\Tests\UnitTestFixture;

class DigitalCardsClientTest extends UnitTestFixture
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
    public function shouldGetDigitalCard()
    {
        $digitalCardId = "dcr_5ngxzsynm2me3oxf73esbhda6q";

        $this->apiClient
            ->method("get")
            ->with(
                $this->equalTo("issuing/digital-cards/" . $digitalCardId),
                $this->anything()
            )
            ->willReturn($this->buildGetDigitalCardResponse());

        $response = $this->client->getDigitalCard($digitalCardId);

        $this->validateGetDigitalCardResponse($response, $digitalCardId);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetDigitalCardWithCompleteResponse()
    {
        $digitalCardId = "dcr_anothervaliddigitalcardid123";

        $this->apiClient
            ->method("get")
            ->with(
                $this->equalTo("issuing/digital-cards/" . $digitalCardId),
                $this->anything()
            )
            ->willReturn($this->buildCompleteDigitalCardResponse());

        $response = $this->client->getDigitalCard($digitalCardId);

        $this->validateCompleteDigitalCardResponse($response, $digitalCardId);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetDigitalCardWithMinimalResponse()
    {
        $digitalCardId = "dcr_minimalresponse123456789xyz";

        $this->apiClient
            ->method("get")
            ->with(
                $this->equalTo("issuing/digital-cards/" . $digitalCardId),
                $this->anything()
            )
            ->willReturn($this->buildMinimalDigitalCardResponse());

        $response = $this->client->getDigitalCard($digitalCardId);

        $this->validateMinimalDigitalCardResponse($response, $digitalCardId);
    }

    // Common builder methods for DRY setup
    private function buildGetDigitalCardResponse(): array
    {
        return [
            "id" => "dcr_5ngxzsynm2me3oxf73esbhda6q",
            "last_four" => "4242",
            "status" => "active",
            "type" => "secure_element",
            "bin" => "411111",
            "scheme" => "visa",
            "cardholder_id" => "crh_3adgx4a7fz2e7bxqa6gblwmu4i",
            "card_id" => "crd_fa6psq242dcd6fdn5gifcx1mwe",
            "created_time" => "2024-03-11T10:30:00Z",
            "display_name" => "John Doe Card"
        ];
    }

    private function buildCompleteDigitalCardResponse(): array
    {
        return [
            "id" => "dcr_anothervaliddigitalcardid123",
            "last_four" => "1234",
            "status" => "active",
            "type" => "secure_element",
            "bin" => "424242",
            "scheme" => "mastercard",
            "cardholder_id" => "crh_5ja6psq242dcd6fdn5gifcx1mwe",
            "card_id" => "crd_8adgx4a7fz2e7bxqa6gblwmu4i",
            "created_time" => "2024-02-15T14:22:30Z",
            "last_modified_time" => "2024-03-01T09:15:45Z",
            "display_name" => "Premium Business Card",
            "expiry_month" => 12,
            "expiry_year" => 2027,
            "device" => [
                "type" => "mobile_phone",
                "name" => "iPhone 15 Pro",
                "phone_number" => "+441234567890"
            ],
            "requestor" => [
                "name" => "Apple Pay",
                "id" => "50110030273"
            ],
            "metadata" => [
                "account_type" => "premium",
                "customer_tier" => "gold"
            ]
        ];
    }

    private function buildMinimalDigitalCardResponse(): array
    {
        return [
            "id" => "dcr_minimalresponse123456789xyz",
            "last_four" => "5678",
            "status" => "suspended",
            "type" => "hce",
            "cardholder_id" => "crh_minimaltest123456789",
            "card_id" => "crd_minimaltest987654321"
        ];
    }

    private function validateGetDigitalCardResponse(array $response, string $expectedDigitalCardId): void
    {
        $this->assertNotNull($response);
        $this->assertArrayHasKey("id", $response);
        $this->assertArrayHasKey("last_four", $response);
        $this->assertArrayHasKey("status", $response);
        $this->assertArrayHasKey("type", $response);
        $this->assertArrayHasKey("cardholder_id", $response);
        $this->assertArrayHasKey("card_id", $response);

        $this->assertEquals($expectedDigitalCardId, $response["id"]);
        $this->assertNotEmpty($response["last_four"]); // Validate it's not empty instead of exact value
        $this->assertTrue(in_array($response["status"], ["active", "suspended", "inactive"]));
        $this->assertTrue(in_array($response["type"], ["secure_element", "hce"]));
    }

    private function validateCompleteDigitalCardResponse(array $response, string $expectedDigitalCardId): void
    {
        $this->validateGetDigitalCardResponse($response, $expectedDigitalCardId);
        
        // Additional validations for complete response
        $this->assertArrayHasKey("bin", $response);
        $this->assertArrayHasKey("scheme", $response);
        $this->assertArrayHasKey("created_time", $response);
        $this->assertArrayHasKey("display_name", $response);
        $this->assertArrayHasKey("expiry_month", $response);
        $this->assertArrayHasKey("expiry_year", $response);
        
        $this->assertEquals("424242", $response["bin"]);
        $this->assertEquals("mastercard", $response["scheme"]);
        $this->assertEquals("Premium Business Card", $response["display_name"]);
        $this->assertEquals(12, $response["expiry_month"]);
        $this->assertEquals(2027, $response["expiry_year"]);

        // Validate nested objects
        if (isset($response["device"])) {
            $this->assertTrue(is_array($response["device"]));
            $this->assertArrayHasKey("type", $response["device"]);
        }

        if (isset($response["requestor"])) {
            $this->assertTrue(is_array($response["requestor"]));
            $this->assertArrayHasKey("name", $response["requestor"]);
            $this->assertArrayHasKey("id", $response["requestor"]);
        }

        if (isset($response["metadata"])) {
            $this->assertTrue(is_array($response["metadata"]));
        }
    }

    private function validateMinimalDigitalCardResponse(array $response, string $expectedDigitalCardId): void
    {
        $this->assertNotNull($response);
        $this->assertArrayHasKey("id", $response);
        $this->assertArrayHasKey("last_four", $response);
        $this->assertArrayHasKey("status", $response);
        $this->assertArrayHasKey("type", $response);

        $this->assertEquals($expectedDigitalCardId, $response["id"]);
        $this->assertEquals("5678", $response["last_four"]);
        $this->assertEquals("suspended", $response["status"]);
        $this->assertEquals("hce", $response["type"]);
    }
}
