<?php

namespace Checkout\Tests\Payments\Four;

use Checkout\Common\Currency;
use Checkout\Payments\Four\Request\PaymentRequest;
use Checkout\Payments\Four\Request\Source\Apm\RequestIdealSource;
use Checkout\Payments\Four\Request\Source\Apm\RequestSofortSource;

class RequestApmPaymentsIntegrationTest extends AbstractPaymentsIntegrationTest
{
    /**
     * @test
     */
    public function shouldMakeIdealPayment()
    {
        $requestSource = new RequestIdealSource();
        $requestSource->bic = "INGBNL2A";
        $requestSource->description = "ORD50234E89";
        $requestSource->language = "nl";

        $paymentRequest = new PaymentRequest();
        $paymentRequest->source = $requestSource;
        $paymentRequest->capture = true;
        $paymentRequest->amount = 1000;
        $paymentRequest->currency = Currency::$EUR;
        $paymentRequest->success_url = "https://testing.checkout.com/sucess";
        $paymentRequest->failure_url = "https://testing.checkout.com/failure";

        $paymentResponse1 = $this->retriable(
            function () use (&$paymentRequest) {
                return $this->fourApi->getPaymentsClient()->requestPayment($paymentRequest);
            });

        $this->assertResponse($paymentResponse1,
            "id",
            "status",
            "_links",
            "_links.self",
            "_links.redirect");

        $paymentResponse2 = $this->retriable(
            function () use (&$paymentResponse1) {
                return $this->fourApi->getPaymentsClient()->getPaymentDetails($paymentResponse1["id"]);
            });

        $this->assertResponse($paymentResponse2,
            "id",
            "requested_on",
            "source",
            "amount",
            //"balances",
            "currency",
            "payment_type",
            "status");
    }

    /**
     * @test
     */
    public function shouldMakeSofortPayment()
    {
        $requestSource = new RequestSofortSource();

        $paymentRequest = new PaymentRequest();
        $paymentRequest->source = $requestSource;
        $paymentRequest->capture = true;
        $paymentRequest->amount = 100;
        $paymentRequest->currency = Currency::$EUR;
        $paymentRequest->success_url = "https://testing.checkout.com/sucess";
        $paymentRequest->failure_url = "https://testing.checkout.com/failure";

        $paymentResponse1 = $this->retriable(
            function () use (&$paymentRequest) {
                return $this->fourApi->getPaymentsClient()->requestPayment($paymentRequest);
            });

        $this->assertResponse($paymentResponse1,
            "id",
            "status",
            "_links",
            "_links.self",
            "_links.redirect");

        $paymentResponse2 = $this->retriable(
            function () use (&$paymentResponse1) {
                return $this->fourApi->getPaymentsClient()->getPaymentDetails($paymentResponse1["id"]);
            });

        $this->assertResponse($paymentResponse2,
            "id",
            "requested_on",
            "source",
            "amount",
            //"balances",
            "currency",
            "payment_type",
            "status");
    }
}
