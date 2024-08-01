<?php

namespace Checkout\Tests\Payments;

use Checkout\CheckoutApiException;
use Checkout\Common\Currency;
use Checkout\Common\CustomerRequest;
use Checkout\Payments\AuthorizationRequest;
use Checkout\Payments\AuthorizationType;
use Checkout\Payments\Request\PartialAuthorization;
use Checkout\Payments\Request\PaymentRequest;
use Checkout\Payments\Request\Source\RequestCardSource;
use Checkout\Payments\Sender\PaymentIndividualSender;
use Checkout\Tests\TestCardSource;

class IncrementPaymentsAuthorizationsTest extends AbstractPaymentsIntegrationTest
{
    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldIncrementPaymentAuthorization()
    {
        $paymentResponse = $this->makeEstimatedAuthorizedPayment();

        $authorizationRequest = new AuthorizationRequest();
        $authorizationRequest->amount = 10;
        $authorizationRequest->reference = uniqid();
        $authorizationRequest->metadata = array("param1" => "value1", "param2" => "value2");

        $authorizationResponse = $this->checkoutApi->getPaymentsClient()->incrementPaymentAuthorization(
            $paymentResponse["id"],
            $authorizationRequest
        );

        $this->assertResponse(
            $authorizationResponse,
            "amount",
            "action_id",
            "currency",
            "response_code",
            "response_summary",
            "expires_on",
            "processed_on",
            "balances",
            "response_summary",
            "risk",
            "_links"
        );
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldIncrementPaymentAuthorizationIdempotent()
    {
        $paymentResponse = $this->makeEstimatedAuthorizedPayment();

        $authorizationRequest = new AuthorizationRequest();
        $authorizationRequest->amount = 10;
        $authorizationRequest->reference = uniqid();
        $authorizationRequest->metadata = array("param1" => "value1", "param2" => "value2");

        $idempotencyKey = $this->idempotencyKey();

        $authorizationResponse = $this->checkoutApi->getPaymentsClient()->incrementPaymentAuthorization(
            $paymentResponse["id"],
            $authorizationRequest,
            $idempotencyKey
        );
        $authorizationResponse2 = $this->checkoutApi->getPaymentsClient()->incrementPaymentAuthorization(
            $paymentResponse["id"],
            $authorizationRequest,
            $idempotencyKey
        );

        $this->assertEquals($authorizationResponse["action_id"], $authorizationResponse2["action_id"]);
    }

    /**
     * @return mixed
     * @throws CheckoutApiException
     */
    private function makeEstimatedAuthorizedPayment()
    {
        $address = $this->getAddress();
        $requestCardSource = new RequestCardSource();
        $requestCardSource->name = TestCardSource::$VisaName;
        $requestCardSource->number = "4556447238607884";
        $requestCardSource->expiry_year = TestCardSource::$VisaExpiryYear;
        $requestCardSource->expiry_month = TestCardSource::$VisaExpiryMonth;
        $requestCardSource->cvv = TestCardSource::$VisaCvv;
        $requestCardSource->billing_address = $address;
        $requestCardSource->phone = $this->getPhone();

        $customerRequest = new CustomerRequest();
        $customerRequest->email = $this->randomEmail();
        $customerRequest->name = "Customer";

        $paymentIndividualSender = new PaymentIndividualSender();
        $paymentIndividualSender->first_name = "Mr";
        $paymentIndividualSender->last_name = "Test";
        $paymentIndividualSender->address = $address;

        $partialAuthorization = new PartialAuthorization();
        $partialAuthorization->enabled = true;

        $paymentRequest = new PaymentRequest();
        $paymentRequest->source = $requestCardSource;
        $paymentRequest->capture = false;
        $paymentRequest->reference = uniqid();
        $paymentRequest->amount = 10;
        $paymentRequest->authorization_type = AuthorizationType::$estimated;
        $paymentRequest->partial_authorization = $partialAuthorization;
        $paymentRequest->currency = Currency::$EUR;
        $paymentRequest->customer = $customerRequest;
        $paymentRequest->sender = $paymentIndividualSender;

        $paymentResponse = $this->checkoutApi->getPaymentsClient()->requestPayment($paymentRequest);

        $this->assertResponse($paymentResponse, "id");

        return $paymentResponse;
    }
}
