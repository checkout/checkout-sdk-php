<?php

namespace Checkout\Tests\Payments\Contexts;

use Checkout\CheckoutApiException;
use Checkout\Payments\Contexts\PaymentContextsClient;
use Checkout\Payments\Contexts\PaymentContextsRequest;
use Checkout\PlatformType;
use Checkout\Tests\UnitTestFixture;

class PaymentContextsClientTest extends UnitTestFixture
{
    /**
     * @var PaymentContextsClient
     */
    private $client;

    /**
     * @before
     */
    public function init()
    {
        $this->initMocks(PlatformType::$default);
        $this->client = new PaymentContextsClient($this->apiClient, $this->configuration);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreatePaymentContexts()
    {

        $this->apiClient
            ->method("post")
            ->willReturn("response");

        $response = $this->client->createPaymentContexts(new PaymentContextsRequest());
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetPaymentContextDetails()
    {

        $this->apiClient
            ->method("get")
            ->willReturn("response");

        $response = $this->client->getPaymentContextDetails("id");
        $this->assertNotNull($response);
    }
}
