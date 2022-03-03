<?php

namespace Checkout\Tests\Apm\Klarna;

use Checkout\Apm\Klarna\CreditSessionRequest;
use Checkout\Apm\Klarna\KlarnaClient;
use Checkout\Apm\Klarna\OrderCaptureRequest;
use Checkout\CheckoutApiException;
use Checkout\Payments\VoidRequest;
use Checkout\PlatformType;
use Checkout\Tests\UnitTestFixture;

class KlarnaClientTest extends UnitTestFixture
{
    private KlarnaClient $client;

    /**
     * @before
     */
    public function init(): void
    {
        $this->initMocks(PlatformType::$default);
        $this->client = new KlarnaClient($this->apiClient, $this->configuration);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreateCreditSession(): void
    {
        $this->apiClient
            ->method("post")
            ->willReturn("foo");

        $response = $this->client->createCreditSession(new CreditSessionRequest());
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetCreditSession(): void
    {
        $this->apiClient
            ->method("get")
            ->willReturn("foo");

        $response = $this->client->getCreditSession("session_id");
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function capturePayment(): void
    {
        $this->apiClient
            ->method("post")
            ->willReturn("foo");

        $response = $this->client->capturePayment("payment_id", new OrderCaptureRequest());
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldVoidPayment(): void
    {
        $this->apiClient
            ->method("post")
            ->willReturn("foo");

        $response = $this->client->voidPayment("payment_id", new VoidRequest());
        $this->assertNotNull($response);
    }

}
