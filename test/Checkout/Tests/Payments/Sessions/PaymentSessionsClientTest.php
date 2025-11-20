<?php

namespace Checkout\Tests\Payments\Sessions;

use Checkout\CheckoutApiException;
use Checkout\Payments\Sessions\PaymentSessionsClient;
use Checkout\Payments\Sessions\PaymentSessionsRequest;
use Checkout\Payments\Sessions\PaymentSessionCompleteRequest;
use Checkout\Payments\Sessions\PaymentSessionSubmitRequest;
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

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCompletePaymentSession()
    {
        $this->apiClient
            ->method("post")
            ->willReturn(["id" => "pay_mbabizu24mvu3mela5njyhpit4", "status" => "Approved"]);

        $request = new PaymentSessionCompleteRequest();
        $request->session_data = "test_session_data";
        $request->amount = 1000;
        $request->currency = "USD";

        $response = $this->client->completePaymentSession($request);
        $this->assertNotNull($response);
        $this->assertEquals("pay_mbabizu24mvu3mela5njyhpit4", $response["id"]);
        $this->assertEquals("Approved", $response["status"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldSubmitPaymentSession()
    {
        $sessionId = "ps_2Un6I6lRpIAiIEwQIyxWVnV9CqQ";
        
        $this->apiClient
            ->method("post")
            ->willReturn(["id" => "pay_mbabizu24mvu3mela5njyhpit4", "status" => "Approved"]);

        $request = new PaymentSessionSubmitRequest();
        $request->session_data = "test_session_data";
        $request->amount = 1000;
        $request->reference = "ORD-123A";

        $response = $this->client->submitPaymentSession($sessionId, $request);
        $this->assertNotNull($response);
        $this->assertEquals("pay_mbabizu24mvu3mela5njyhpit4", $response["id"]);
        $this->assertEquals("Approved", $response["status"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldHandleCompletePaymentSessionException()
    {
        $this->expectException(CheckoutApiException::class);

        $this->apiClient
            ->method("post")
            ->willThrowException(new CheckoutApiException("Test exception"));

        $request = new PaymentSessionCompleteRequest();
        $this->client->completePaymentSession($request);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldHandleSubmitPaymentSessionException()
    {
        $this->expectException(CheckoutApiException::class);

        $this->apiClient
            ->method("post")
            ->willThrowException(new CheckoutApiException("Test exception"));

        $request = new PaymentSessionSubmitRequest();
        $this->client->submitPaymentSession("ps_test_session_id", $request);
    }

}
