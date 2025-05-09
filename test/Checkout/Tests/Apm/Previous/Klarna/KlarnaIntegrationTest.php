<?php

namespace Checkout\Tests\Apm\Previous\Klarna;

use Checkout\Apm\Previous\Klarna\CreditSessionRequest;
use Checkout\Apm\Previous\Klarna\KlarnaProduct;
use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\Common\Country;
use Checkout\Common\Currency;
use Checkout\PlatformType;
use Checkout\Tests\SandboxTestFixture;

class KlarnaIntegrationTest extends SandboxTestFixture
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
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreateAndGetKlarnaSession()
    {
        $this->markTestSkipped("unavailable");
        $klarnaProduct = new KlarnaProduct();
        $klarnaProduct->name = "Brown leather belt";
        $klarnaProduct->quantity = 1;
        $klarnaProduct->unit_price = 1000;
        $klarnaProduct->tax_rate = 0;
        $klarnaProduct->total_amount = 1000;
        $klarnaProduct->total_tax_amount = 0;

        $creditSessionRequest = new CreditSessionRequest();
        $creditSessionRequest->purchase_country = Country::$GB;
        $creditSessionRequest->currency = Currency::$GBP;
        $creditSessionRequest->locale = "en-GB";
        $creditSessionRequest->amount = 1000;
        $creditSessionRequest->tax_amount = 1;
        $creditSessionRequest->products = array($klarnaProduct);

        $creditSessionResponse = $this->previousApi->getKlarnaClient()->createCreditSession($creditSessionRequest);

        $this->assertResponse(
            $creditSessionResponse,
            "session_id",
            "client_token",
            "payment_method_categories"
        );


        foreach ($creditSessionResponse["payment_method_categories"] as $paymentMethodCategory) {
            $this->assertResponse(
                $paymentMethodCategory,
                "name",
                "identifier",
                "asset_urls"
            );
        }

        $creditSession = $this->previousApi->getKlarnaClient()->getCreditSession($creditSessionResponse["session_id"]);

        $this->assertResponse(
            $creditSession,
            "client_token",
            "purchase_country",
            "currency",
            "locale",
            "amount",
            "tax_amount",
            "products"
        );

        foreach ($creditSession["products"] as $creditSessionProduct) {
            $this->assertResponse(
                $creditSessionProduct,
                "name",
                "quantity",
                "unit_price",
                "total_amount"
            );
        }
    }
}
