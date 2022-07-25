<?php

namespace Checkout\Tests\Payments\Previous;

use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\Common\Currency;
use Checkout\Common\CustomerRequest;
use Checkout\Payments\Previous\PaymentRequest;
use Checkout\Payments\Previous\Source\RequestCardSource;
use Checkout\Payments\Previous\Source\RequestTokenSource;
use Checkout\Payments\ThreeDsRequest;
use Checkout\PlatformType;
use Checkout\Tests\SandboxTestFixture;
use Checkout\Tests\TestCardSource;
use Checkout\Tokens\CardTokenRequest;
use DateTime;

abstract class AbstractPaymentsIntegrationTest extends SandboxTestFixture
{

    /**
     * @before
     * @throws CheckoutAuthorizationException
     * @throws CheckoutArgumentException
     * @throws CheckoutException
     */
    public function before()
    {
        $this->init(PlatformType::$previous);
    }

    /**
     * @param bool|null $shouldCapture
     * @param int $amount
     * @param DateTime|null $captureOn
     * @return array
     * @throws CheckoutApiException
     */
    protected function makeCardPayment($shouldCapture = false, $amount = 10, $captureOn = null)
    {
        $phone = $this->getPhone();
        $billingAddress = $this->getAddress();

        $requestCardSource = new RequestCardSource();
        $requestCardSource->name = TestCardSource::$VisaName;
        $requestCardSource->number = TestCardSource::$VisaNumber;
        $requestCardSource->expiry_year = TestCardSource::$VisaExpiryYear;
        $requestCardSource->expiry_month = TestCardSource::$VisaExpiryMonth;
        $requestCardSource->cvv = TestCardSource::$VisaCvv;
        $requestCardSource->billing_address = $billingAddress;
        $requestCardSource->phone = $phone;

        $paymentRequest = new PaymentRequest();
        $paymentRequest->source = $requestCardSource;
        $paymentRequest->capture = $shouldCapture;
        $paymentRequest->reference = uniqid("makeCardPayment");
        $paymentRequest->amount = $amount;
        $paymentRequest->currency = Currency::$GBP;

        if (!is_null($captureOn)) {
            $paymentRequest->capture_on = $captureOn;
        }

        $paymentResponse = $this->previousApi->getPaymentsClient()->requestPayment($paymentRequest);
        $this->assertResponse($paymentResponse, "id");
        return $paymentResponse;
    }

    /**
     * @return array
     * @throws CheckoutApiException
     */
    protected function makeTokenPayment()
    {
        $phone = $this->getPhone();
        $billingAddress = $this->getAddress();

        $cardTokenRequest = new CardTokenRequest();
        $cardTokenRequest->name = TestCardSource::$VisaName;
        $cardTokenRequest->number = TestCardSource::$VisaNumber;
        $cardTokenRequest->expiry_year = TestCardSource::$VisaExpiryYear;
        $cardTokenRequest->expiry_month = TestCardSource::$VisaExpiryMonth;
        $cardTokenRequest->cvv = TestCardSource::$VisaCvv;
        $cardTokenRequest->billing_address = $billingAddress;
        $cardTokenRequest->phone = $phone;

        $cardTokenResponse = $this->previousApi->getTokensClient()->requestCardToken($cardTokenRequest);
        $this->assertResponse($cardTokenResponse, "token");

        $requestTokenSource = new RequestTokenSource();
        $requestTokenSource->token = $cardTokenResponse["token"];

        $customerRequest = new CustomerRequest();
        $customerRequest->email = $this->randomEmail();

        $paymentRequest = new PaymentRequest();
        $paymentRequest->source = $requestTokenSource;
        $paymentRequest->capture = true;
        $paymentRequest->reference = uniqid("makeTokenPayment");
        $paymentRequest->amount = 10;
        $paymentRequest->currency = Currency::$USD;
        $paymentRequest->customer = $customerRequest;

        $paymentResponse = $this->previousApi->getPaymentsClient()->requestPayment($paymentRequest);
        $this->assertResponse($paymentResponse, "id");

        return $paymentResponse;
    }

    /**
     * @param bool $attemptN3d
     * @return array
     * @throws CheckoutApiException
     */
    protected function make3dsCardPayment($attemptN3d = false)
    {
        $phone = $this->getPhone();
        $billingAddress = $this->getAddress();

        $requestCardSource = new RequestCardSource();
        $requestCardSource->name = TestCardSource::$VisaName;
        $requestCardSource->number = TestCardSource::$VisaNumber;
        $requestCardSource->expiry_year = TestCardSource::$VisaExpiryYear;
        $requestCardSource->expiry_month = TestCardSource::$VisaExpiryMonth;
        $requestCardSource->cvv = TestCardSource::$VisaCvv;
        $requestCardSource->billing_address = $billingAddress;
        $requestCardSource->phone = $phone;

        $threeDsRequest = new ThreeDsRequest();
        $threeDsRequest->enabled = true;
        $threeDsRequest->attempt_n3d = $attemptN3d;
        $threeDsRequest->eci = $attemptN3d ? "05" : "";
        $threeDsRequest->cryptogram = $attemptN3d ? "AgAAAAAAAIR8CQrXcIhbQAAAAAA" : "";
        $threeDsRequest->xid = $attemptN3d ? "MDAwMDAwMDAwMDAwMDAwMzIyNzY" : "";
        $threeDsRequest->version = "2.0.1";

        $customerRequest = new CustomerRequest();
        $customerRequest->email = $this->randomEmail();

        $paymentRequest = new PaymentRequest();
        $paymentRequest->source = $requestCardSource;
        $paymentRequest->capture = false;
        $paymentRequest->reference = uniqid("make3dsCardPayment");
        $paymentRequest->amount = 10;
        $paymentRequest->currency = Currency::$USD;
        $paymentRequest->customer = $customerRequest;
        $paymentRequest->three_ds = $threeDsRequest;

        $paymentResponse = $this->previousApi->getPaymentsClient()->requestPayment($paymentRequest);
        $this->assertNotNull($paymentResponse);

        return $paymentResponse;
    }

}
