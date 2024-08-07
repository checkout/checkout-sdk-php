<?php

namespace Checkout\Tests\Payments\Sessions;

use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\Common\Currency;
use Checkout\Common\CustomerRequest;
use Checkout\Payments\BillingInformation;
use Checkout\Payments\Sessions\PaymentSessionsRequest;
use Checkout\PlatformType;
use Checkout\Tests\SandboxTestFixture;

class PaymentSessionsIntegrationTest extends SandboxTestFixture
{
    /**
     * @before
     * @throws CheckoutAuthorizationException
     * @throws CheckoutArgumentException
     * @throws CheckoutException
     */
    public function before()
    {
        $this->init(PlatformType::$default);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreatePaymentsSessions()
    {
        $request = $this->createPaymentSessionsRequest();

        $response = $this->checkoutApi->getPaymentSessionsClient()->createPaymentSessions($request);

        $this->assertResponse(
            $response,
            "id",
            "payment_session_token",
            "_links",
            "_links.self"
        );
    }

    private function createPaymentSessionsRequest()
    {
        $billing = new BillingInformation();
        $billing->address = $this->getAddress();

        $customer = new CustomerRequest();
        $customer->name = "John Smith";
        $customer->email = "john.smith@example.com";

        $paymentSessionsRequest = new PaymentSessionsRequest();
        $paymentSessionsRequest->amount = 2000;
        $paymentSessionsRequest->currency = Currency::$GBP;
        $paymentSessionsRequest->reference = "ORD-123A";
        $paymentSessionsRequest->billing = $billing;
        $paymentSessionsRequest->success_url = "https://example.com/payments/success";
        $paymentSessionsRequest->failure_url = "https://example.com/payments/failure";

        return $paymentSessionsRequest;
    }
}
