<?php

namespace Checkout\Tests\Payments;

use Checkout\CheckoutApiException;
use Checkout\Common\Currency;
use Checkout\Payments\CaptureRequest;
use Checkout\Payments\PaymentRequest;
use Checkout\Payments\PaymentsClient;
use Checkout\Payments\PayoutRequest;
use Checkout\Payments\RefundRequest;
use Checkout\Payments\VoidRequest;
use Checkout\PlatformType;
use Checkout\Tests\UnitTestFixture;


class PaymentsClientTest extends UnitTestFixture
{
    private $client;

    /**
     * @before
     */
    public function init()
    {
        $this->initMocks(PlatformType::$default);
        $this->client = new PaymentsClient($this->apiClient, $this->configuration);
    }


    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldRequestPayment()
    {

        $this->apiClient
            ->method("post")
            ->willReturn("foo");

        $response = $this->client->requestPayment(new PaymentRequest());
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldRequestPayment_customSource()
    {

        $this->apiClient
            ->method("post")
            ->willReturn("foo");

        $customSource = new CustomSource();
        $customSource->amount = 10;
        $customSource->currency = Currency::$USD;

        $request = new PaymentRequest();
        $request->source = $customSource;
        $response = $this->client->requestPayment($request);
        $this->assertNotNull($response);
    }


    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldRequestPayout()
    {

        $this->apiClient
            ->method("post")
            ->willReturn("foo");

        $response = $this->client->requestPayout(new PayoutRequest());
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetPaymentDetails()
    {

        $this->apiClient
            ->method("get")
            ->willReturn("foo");

        $response = $this->client->getPaymentDetails("payment_id");
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetPaymentActions()
    {

        $this->apiClient
            ->method("get")
            ->willReturn("foo");

        $response = $this->client->getPaymentActions("payment_id");
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCapturePayment()
    {

        $this->apiClient
            ->method("post")
            ->willReturn("foo");

        $response = $this->client->capturePayment("payment_id", new CaptureRequest());
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldRefundPayment()
    {

        $this->apiClient
            ->method("post")
            ->willReturn("foo");

        $response = $this->client->refundPayment("payment_id", new RefundRequest());
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldVoidPayment()
    {

        $this->apiClient
            ->method("post")
            ->willReturn("foo");

        $response = $this->client->voidPayment("payment_id", new VoidRequest());
        $this->assertNotNull($response);
    }

}
