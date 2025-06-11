<?php

namespace Checkout\Tests\Payments;

use Checkout\CheckoutApiException;
use Checkout\Common\AccountHolder;
use Checkout\Payments\AuthorizationRequest;
use Checkout\Payments\CaptureRequest;
use Checkout\Payments\PaymentsClient;
use Checkout\Payments\PaymentsQueryFilter;
use Checkout\Payments\Request\PaymentRequest;
use Checkout\Payments\Request\PayoutRequest;
use Checkout\Payments\Request\Source\RequestProviderTokenSource;
use Checkout\Payments\RefundRequest;
use Checkout\Payments\VoidRequest;
use Checkout\PlatformType;
use Checkout\Tests\UnitTestFixture;

class PaymentsClientTest extends UnitTestFixture
{
    /**
     * @var PaymentsClient
     */
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

        $source = new RequestProviderTokenSource();
        $source->payment_method = "method";
        $source->token = "token";
        $source->account_holder = new AccountHolder();

        $request = new PaymentRequest();
        $request->source = $source;

        $this->apiClient
            ->method("post")
            ->willReturn(["response"]);

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
            ->willReturn(["response"]);

        $response = $this->client->requestPayout(new PayoutRequest());
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetPaymentsList()
    {
        $this->apiClient
            ->method("query")
            ->willReturn(["response"]);

        $response = $this->client->getPaymentsList(new PaymentsQueryFilter());
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
            ->willReturn(["response"]);

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
            ->willReturn(["response"]);

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
            ->willReturn(["response"]);

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
            ->willReturn(["response"]);

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
            ->willReturn(["response"]);

        $response = $this->client->voidPayment("payment_id", new VoidRequest());
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldIncrementPaymentAuthorization()
    {

        $this->apiClient
            ->method("post")
            ->willReturn(["response"]);

        $response = $this->client->incrementPaymentAuthorization("payment_id", new AuthorizationRequest());
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldIncrementPaymentAuthorizationIdempotently()
    {

        $this->apiClient
            ->method("post")
            ->willReturn(["response"]);

        $response = $this->client->incrementPaymentAuthorization(
            "payment_id",
            new AuthorizationRequest(),
            "idempotency_key"
        );
        $this->assertNotNull($response);
    }
}
