<?php

namespace Checkout\Tests\Payments\Four;

use Checkout\CheckoutApiException;
use Checkout\Common\Currency;
use Checkout\Common\CustomerRequest;
use Checkout\Payments\Four\AuthorizationRequest;
use Checkout\Payments\Four\AuthorizationType;
use Checkout\Payments\Four\Request\PaymentRequest;
use Checkout\Payments\Four\Request\Source\RequestCardSource;
use Checkout\Payments\Four\Sender\PaymentIndividualSender;
use Checkout\Tests\TestCardSource;

class IncrementPaymentsAuthorizationsTest extends AbstractPaymentsIntegrationTest
{
    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldIncrementPaymentAuthorization(): void
    {
        $paymentResponse = $this->makeEstimatedAuthorizedPayment();

        $authorizationRequest = new AuthorizationRequest();
        $authorizationRequest->amount = 10;
        $authorizationRequest->reference = uniqid();
        $authorizationRequest->metadata = array("param1" => "value1", "param2" => "value2");

        $authorizationResponse = $this->fourApi->getPaymentsClient()->incrementPaymentAuthorization($paymentResponse["id"], $authorizationRequest);

        self::assertResponse($authorizationResponse,
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
            "_links");
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldIncrementPaymentAuthorization_idempotently(): void
    {
        $paymentResponse = $this->makeEstimatedAuthorizedPayment();

        $authorizationRequest = new AuthorizationRequest();
        $authorizationRequest->amount = 10;
        $authorizationRequest->reference = uniqid();
        $authorizationRequest->metadata = array("param1" => "value1", "param2" => "value2");

        $idempotencyKey = $this->idempotencyKey();

        $authorizationResponse = $this->fourApi->getPaymentsClient()->incrementPaymentAuthorization($paymentResponse["id"], $authorizationRequest, $idempotencyKey);
        $authorizationResponse2 = $this->fourApi->getPaymentsClient()->incrementPaymentAuthorization($paymentResponse["id"], $authorizationRequest, $idempotencyKey);

        self::assertEquals($authorizationResponse["action_id"], $authorizationResponse2["action_id"]);
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
        $paymentIndividualSender->fist_name = "Mr";
        $paymentIndividualSender->last_name = "Test";
        $paymentIndividualSender->address = $address;

        $paymentRequest = new PaymentRequest();
        $paymentRequest->source = $requestCardSource;
        $paymentRequest->capture = false;
        $paymentRequest->reference = uniqid();
        $paymentRequest->amount = 10;
        $paymentRequest->authorization_type = AuthorizationType::$estimated;
        $paymentRequest->currency = Currency::$EUR;
        $paymentRequest->customer = $customerRequest;
        $paymentRequest->sender = $paymentIndividualSender;

        $paymentResponse = $this->fourApi->getPaymentsClient()->requestPayment($paymentRequest);

        $this->assertResponse($paymentResponse, "id");

        return $paymentResponse;
    }
}
