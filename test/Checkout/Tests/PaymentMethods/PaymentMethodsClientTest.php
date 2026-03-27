<?php

namespace Checkout\Tests\PaymentMethods;

use Checkout\PaymentMethods\PaymentMethodsClient;
use Checkout\PaymentMethods\Requests\PaymentMethodsQuery;
use Checkout\PaymentMethods\Entities\PaymentMethodType;
use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\PlatformType;
use Checkout\Tests\UnitTestFixture;

class PaymentMethodsClientTest extends UnitTestFixture
{
    /**
     * @var PaymentMethodsClient
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
        $this->initMocks(PlatformType::$default_oauth);
        $this->client = new PaymentMethodsClient(
            $this->apiClient,
            $this->configuration
        );
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetAvailablePaymentMethods()
    {
        $this->apiClient
            ->method("query")
            ->willReturn($this->buildGetAvailablePaymentMethodsResponse());

        $query = $this->buildPaymentMethodsQuery();
        $response = $this->client->getAvailablePaymentMethods($query);

        $this->assertNotNull($response);
        $this->assertArrayHasKey("methods", $response);
        $this->assertTrue(is_array($response["methods"]));
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetAvailablePaymentMethodsWithValidProcessingChannelId()
    {
        $expectedResponse = $this->buildGetAvailablePaymentMethodsResponse();
        
        $this->apiClient
            ->method("query")
            ->willReturn($expectedResponse);

        $query = $this->buildPaymentMethodsQuery("pc_test_123456");
        $response = $this->client->getAvailablePaymentMethods($query);

        $this->validateGetAvailablePaymentMethodsResponse($response, $expectedResponse);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetAvailablePaymentMethodsWithMultiplePaymentMethods()
    {
        $expectedResponse = $this->buildMultiplePaymentMethodsResponse();
        
        $this->apiClient
            ->method("query")
            ->willReturn($expectedResponse);

        $query = $this->buildPaymentMethodsQuery();
        $response = $this->client->getAvailablePaymentMethods($query);

        $this->assertNotNull($response);
        $this->assertArrayHasKey("methods", $response);
        $this->assertEquals(2, count($response["methods"]));
        $this->validateGetAvailablePaymentMethodsResponse($response, $expectedResponse);
    }

    /**
     * Helper method to build PaymentMethodsQuery
     * @param string $processingChannelId
     * @return PaymentMethodsQuery
     */
    private function buildPaymentMethodsQuery($processingChannelId = "pc_test_123456")
    {
        $query = new PaymentMethodsQuery();
        $query->processing_channel_id = $processingChannelId;
        
        return $query;
    }

    /**
     * Helper method to build GetAvailablePaymentMethods response
     * @return array
     */
    private function buildGetAvailablePaymentMethodsResponse()
    {
        return [
            "methods" => [
                [
                    "type" => PaymentMethodType::$visa,
                    "partner_merchant_id" => "merchant_123"
                ]
            ]
        ];
    }

    /**
     * Helper method to build multiple payment methods response
     * @return array
     */
    private function buildMultiplePaymentMethodsResponse()
    {
        return [
            "methods" => [
                [
                    "type" => PaymentMethodType::$visa,
                    "partner_merchant_id" => "merchant_123"
                ],
                [
                    "type" => PaymentMethodType::$klarna,
                    "partner_merchant_id" => "merchant_456"
                ]
            ]
        ];
    }

    /**
     * Helper method to validate GetAvailablePaymentMethods response
     * @param array $actual
     * @param array $expected
     */
    private function validateGetAvailablePaymentMethodsResponse($actual, $expected)
    {
        $this->assertNotNull($actual);
        $this->assertArrayHasKey("methods", $actual);
        $this->assertTrue(is_array($actual["methods"]));
        $this->assertEquals(count($expected["methods"]), count($actual["methods"]));
        
        foreach ($actual["methods"] as $index => $method) {
            $this->assertArrayHasKey("type", $method);
            $this->assertNotEmpty($method["type"]);
            $this->assertEquals($expected["methods"][$index]["type"], $method["type"]);
            
            if (isset($method["partner_merchant_id"])) {
                $this->assertNotEmpty($method["partner_merchant_id"]);
                $this->assertEquals($expected["methods"][$index]["partner_merchant_id"], $method["partner_merchant_id"]);
            }
        }
    }
}
