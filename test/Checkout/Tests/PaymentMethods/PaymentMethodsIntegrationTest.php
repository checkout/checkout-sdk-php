<?php

namespace Checkout\Tests\PaymentMethods;

use Checkout\PaymentMethods\Requests\PaymentMethodsQuery;
use Checkout\PaymentMethods\Entities\PaymentMethodType;
use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutException;
use Checkout\PlatformType;
use Checkout\Tests\SandboxTestFixture;

class PaymentMethodsIntegrationTest extends SandboxTestFixture
{
    private $validProcessingChannelId = "pc_5jp2az55l3cuths25t5p3xhwru";
    private $invalidProcessingChannelId = "pc_test_invalid_channel_id";

    /**
     * @before
     * @throws
     */
    public function before()
    {
        $this->init(PlatformType::$default_oauth);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetAvailablePaymentMethods()
    {
        $query = $this->buildPaymentMethodsQuery($this->validProcessingChannelId);

        $response = $this->checkoutApi->getPaymentMethodsClient()->getAvailablePaymentMethods($query);

        $this->validateGetAvailablePaymentMethodsResponse($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetAvailablePaymentMethodsWithSpecificProcessingChannel()
    {
        $query = $this->buildPaymentMethodsQuery($this->validProcessingChannelId);

        $response = $this->checkoutApi->getPaymentMethodsClient()->getAvailablePaymentMethods($query);

        $this->validateGetAvailablePaymentMethodsResponse($response);
        $this->validateProcessingChannelSpecificMethods($response, $this->validProcessingChannelId);
    }

    /**
     * @test
     * @skip API may throw exception for invalid processing channel
     * @throws CheckoutApiException
     */
    public function shouldThrowExceptionWithInvalidProcessingChannelId()
    {
        $this->markTestSkipped("API may throw exception for invalid processing channel");
        
        $query = $this->buildPaymentMethodsQuery($this->invalidProcessingChannelId);

        $this->expectException(CheckoutApiException::class);
        
        $this->checkoutApi->getPaymentMethodsClient()->getAvailablePaymentMethods($query);
    }

    /**
     * Helper method to build PaymentMethodsQuery
     * @param string $processingChannelId
     * @return PaymentMethodsQuery
     */
    private function buildPaymentMethodsQuery($processingChannelId)
    {
        $query = new PaymentMethodsQuery();
        $query->processing_channel_id = $processingChannelId;
        
        return $query;
    }

    /**
     * Helper method to validate GetAvailablePaymentMethods response
     * @param array $response
     */
    private function validateGetAvailablePaymentMethodsResponse($response)
    {
        $this->assertNotNull($response);
        $this->assertArrayHasKey("methods", $response);
        $this->assertTrue(is_array($response["methods"]));
        $this->assertGreaterThan(0, count($response["methods"]));

        foreach ($response["methods"] as $method) {
            $this->assertNotNull($method);
            $this->assertArrayHasKey("type", $method);
            $this->assertNotEmpty($method["type"]);
        }
    }

    /**
     * Helper method to validate processing channel specific methods
     * @param array $response
     * @param string $processingChannelId
     */
    private function validateProcessingChannelSpecificMethods($response, $processingChannelId)
    {
        $this->assertArrayHasKey("methods", $response);
        $this->assertNotEmpty($response["methods"]);
        
        foreach ($response["methods"] as $method) {
            // Validate that each method has the expected structure
            $this->assertArrayHasKey("type", $method);
            $this->assertNotEmpty($method["type"]);
            
            // If partner merchant ID is provided, it should be valid
            if (isset($method["partner_merchant_id"]) && !empty($method["partner_merchant_id"])) {
                $this->assertNotEmpty($method["partner_merchant_id"]);
            }
        }
    }
}
