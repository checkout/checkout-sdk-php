<?php

namespace Checkout\Tests\Payments\Four;

use Checkout\CheckoutApiException;
use Checkout\Payments\Four\AuthorizationRequest;
use Checkout\Payments\Four\CaptureRequest;
use Checkout\Payments\Four\PaymentsClient;
use Checkout\Payments\Four\Request\PaymentRequest;
use Checkout\Payments\Four\Request\PayoutRequest;
use Checkout\Payments\RefundRequest;
use Checkout\Payments\VoidRequest;
use Checkout\PlatformType;
use Checkout\Tests\UnitTestFixture;

class PaymentsClientTest extends UnitTestFixture
{
    private PaymentsClient $client;

    /**
     * @before
     */
    public function init(): void
    {
        $this->initMocks(PlatformType::$four);
        $this->client = new PaymentsClient($this->apiClient, $this->configuration);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldRequestPayment(): void
    {

        $this->apiClient
            ->method("post")
            ->willReturn("response");

        $response = $this->client->requestPayment(new PaymentRequest());
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldRequestPayout(): void
    {

        $this->apiClient
            ->method("post")
            ->willReturn("response");

        $response = $this->client->requestPayout(new PayoutRequest());
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetPaymentDetails(): void
    {

        $this->apiClient
            ->method("get")
            ->willReturn("response");

        $response = $this->client->getPaymentDetails("payment_id");
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetPaymentActions(): void
    {

        $this->apiClient
            ->method("get")
            ->willReturn("response");

        $response = $this->client->getPaymentActions("payment_id");
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCapturePayment(): void
    {

        $this->apiClient
            ->method("post")
            ->willReturn("response");

        $response = $this->client->capturePayment("payment_id", new CaptureRequest());
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldRefundPayment(): void
    {

        $this->apiClient
            ->method("post")
            ->willReturn("response");

        $response = $this->client->refundPayment("payment_id", new RefundRequest());
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
            ->willReturn("response");

        $response = $this->client->voidPayment("payment_id", new VoidRequest());
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldIncrementPaymentAuthorization(): void
    {

        $this->apiClient
            ->method("post")
            ->willReturn("response");

        $response = $this->client->incrementPaymentAuthorization("payment_id", new AuthorizationRequest());
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldIncrementPaymentAuthorization_idempotently(): void
    {

        $this->apiClient
            ->method("post")
            ->willReturn("response");

        $response = $this->client->incrementPaymentAuthorization("payment_id", new AuthorizationRequest(), "idempotency_key");
        $this->assertNotNull($response);
    }

}
