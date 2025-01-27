<?php

namespace Checkout\Tests\Payments\Previous;

use Checkout\CheckoutApiException;
use Checkout\Common\Currency;
use Checkout\Payments\Previous\Destination\PaymentRequestCardDestination;
use Checkout\Payments\FundTransferType;
use Checkout\Payments\Previous\PayoutRequest;
use Checkout\Tests\TestCardSource;
use DateTime;

class RequestPayoutsIntegrationTest extends AbstractPaymentsIntegrationTest
{

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldRequestPayout()
    {
        $this->markTestSkipped("unavailable");
        $requestCardDestination = new PaymentRequestCardDestination();
        $requestCardDestination->name = TestCardSource::$VisaName;
        $requestCardDestination->number = TestCardSource::$VisaNumber;
        $requestCardDestination->first_name = TestCardSource::$VisaName;
        $requestCardDestination->last_name = "Integration";
        $requestCardDestination->expiry_year = TestCardSource::$VisaExpiryYear;
        $requestCardDestination->expiry_month = TestCardSource::$VisaExpiryMonth;
        $requestCardDestination->billing_address = $this->getAddress();
        $requestCardDestination->phone = $this->getPhone();

        $payoutRequest = new PayoutRequest();
        $payoutRequest->destination = $requestCardDestination;
        $payoutRequest->capture = false;
        $payoutRequest->reference = uniqid();
        $payoutRequest->amount = 5;
        $payoutRequest->currency = Currency::$USD;
        $payoutRequest->capture_on = new DateTime();
        $payoutRequest->fund_transfer_type = FundTransferType::$FT;

        $paymentResponse = $this->previousApi->getPaymentsClient()->requestPayout($payoutRequest);

        $this->assertResponse(
            $paymentResponse,
            "id",
            "reference",
            "status",
            "customer",
            "customer.id"
        );

        $payment = $this->retriable(
            function () use (&$paymentResponse) {
                return $this->previousApi->getPaymentsClient()->getPaymentDetails($paymentResponse["id"]);
            }
        );

        $this->assertResponse(
            $payment,
            "destination",
            "destination.bin",
            "destination.card_category",
            "destination.card_type",
            "destination.expiry_month",
            "destination.expiry_year",
            "destination.last4",
            "destination.fingerprint",
            "destination.name",
            //"destination.issuer",
            //"destination.issuer_country",
            "destination.product_id",
            "destination.product_type"
        );
    }
}
