<?php

namespace Checkout\Tests\Payments\Sessions;

use Checkout\CheckoutApiException;
use Checkout\Payments\Sessions\PaymentSessionsClient;
use Checkout\Payments\Sessions\PaymentSessionsRequest;
use Checkout\PlatformType;
use Checkout\Tests\UnitTestFixture;

class PaymentSessionsClientTest extends UnitTestFixture
{
    /**
     * @var PaymentSessionsClient
     */
    private $client;

    /**
     * @before
     */
    public function init()
    {
        $this->initMocks(PlatformType::$default);
        $this->client = new PaymentSessionsClient($this->apiClient, $this->configuration);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreatePaymentSessions()
    {

        $this->apiClient
            ->method("post")
            ->willReturn(["response"]);

        $response = $this->client->createPaymentSessions(new PaymentSessionsRequest());
        $this->assertNotNull($response);
    }

}
