<?php

namespace Checkout\Tests\Payments\Contexts;

use Checkout\PlatformType;
use Checkout\Common\Address;
use Checkout\Common\Country;
use Checkout\Common\Currency;
use Checkout\CheckoutException;
use Checkout\CheckoutApiException;
use Checkout\Common\AccountHolder;
use Checkout\Payments\PaymentType;
use Checkout\Tests\SandboxTestFixture;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\Payments\Contexts\PaymentContextsItems;
use Checkout\Payments\Contexts\PaymentContextsRequest;
use Checkout\Payments\Contexts\PaymentContextsProcessing;
use Checkout\Payments\Request\Source\Contexts\PaymentContextsKlarnaSource;
use Checkout\Payments\Request\Source\Contexts\PaymentContextsPayPalSource;

class PaymentContextsIntegrationTest extends SandboxTestFixture
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
    public function shouldCreateAndGetPaymentContextsDetails()
    {
        $request = $this->createPaymentContextsRequest();

        $response = $this->checkoutApi->getPaymentContextsClient()->createPaymentContexts($request);

        $this->assertResponse(
            $response,
            "id",
            "partner_metadata",
            "partner_metadata.order_id",
            "_links",
            "_links.self"
        );

        $getResponse = $this->checkoutApi->getPaymentContextsClient()->getPaymentContextDetails($response["id"]);

        $this->assertResponse(
            $getResponse,
            "payment_request",
            "payment_request.source",
            "payment_request.amount",
            "payment_request.currency",
            "payment_request.payment_type",
            "payment_request.capture",
            "payment_request.success_url",
            "payment_request.failure_url",
            "partner_metadata",
            "partner_metadata.order_id"
        );
        foreach ($getResponse["payment_request"]["items"] as $items) {
            $this->assertResponse(
                $items,
                "name",
                "unit_price",
                "quantity"
            );
        }
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreatePaymentContextsKlarna()
    {
        $processing = new PaymentContextsProcessing();
        $processing->locale = "en-GB";

        $billing_address = new Address();
        $billing_address->country = Country::$DE;

        $account_holder = new AccountHolder();
        $account_holder->billing_address =  $billing_address;

        $source = new PaymentContextsKlarnaSource();
        $source->account_holder = $account_holder;

        $paymentContextItems = new PaymentContextsItems();
        $paymentContextItems->name = "mask";
        $paymentContextItems->unit_price = 1000;
        $paymentContextItems->quantity = 1;
        $paymentContextItems->total_amount = 1000;

        $paymentContextRequest = new PaymentContextsRequest();
        $paymentContextRequest->source = $source;
        $paymentContextRequest->amount = 1000;
        $paymentContextRequest->currency = Currency::$EUR;
        $paymentContextRequest->payment_type = PaymentType::$regular;
        $paymentContextRequest->processing_channel_id = getenv("CHECKOUT_PROCESSING_CHANNEL_ID");
        $paymentContextRequest->items = array($paymentContextItems);
        $paymentContextRequest->processing = $processing;

        $this->checkErrorItem(
            function () use (&$paymentContextRequest) {
                return $this->checkoutApi->getPaymentContextsClient()->createPaymentContexts($paymentContextRequest);
            },
            "apm_service_unavailable"
        );
    }

    private function createPaymentContextsRequest()
    {
        $paymentContextItems = new PaymentContextsItems();
        $paymentContextItems->name = "mask";
        $paymentContextItems->unit_price = 1000;
        $paymentContextItems->quantity = 1;
        $paymentContextItems->total_amount = 1000;

        $paymentContextRequest = new PaymentContextsRequest();
        $paymentContextRequest->source = new PaymentContextsPayPalSource();
        $paymentContextRequest->amount = 1000;
        $paymentContextRequest->currency = Currency::$EUR;
        $paymentContextRequest->payment_type = PaymentType::$regular;
        $paymentContextRequest->capture = true;
        $paymentContextRequest->processing_channel_id = getenv("CHECKOUT_PROCESSING_CHANNEL_ID");
        $paymentContextRequest->success_url = "https://example.com/payments/success";
        $paymentContextRequest->failure_url = "https://example.com/payments/failure";
        $paymentContextRequest->items = array($paymentContextItems);

        return $paymentContextRequest;
    }
}
