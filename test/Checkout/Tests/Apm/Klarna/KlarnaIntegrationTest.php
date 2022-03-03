<?php

namespace Checkout\Tests\Apm\Klarna;

use Checkout\Apm\Klarna\CreditSessionRequest;
use Checkout\Apm\Klarna\KlarnaProduct;
use Checkout\CheckoutApiException;
use Checkout\Common\Country;
use Checkout\Common\Currency;
use Checkout\PlatformType;
use Checkout\Tests\SandboxTestFixture;

class KlarnaIntegrationTest extends SandboxTestFixture
{

    /**
     * @before
     */
    public function before(): void
    {
        $this->init(PlatformType::$default);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreateAndGetKlarnaSession(): void
    {
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
        $creditSessionRequest->products = array($klarnaProduct);;

        $creditSessionResponse = $this->defaultApi->getKlarnaClient()->createCreditSession($creditSessionRequest);

        $this->assertResponse($creditSessionResponse,
            "session_id",
            "client_token",
            "payment_method_categories");


        foreach ($creditSessionResponse["payment_method_categories"] as $paymentMethodCategory) {
            $this->assertResponse($paymentMethodCategory,
                "name",
                "identifier",
                "asset_urls");
        }

        $creditSession = $this->defaultApi->getKlarnaClient()->getCreditSession($creditSessionResponse["session_id"]);

        $this->assertResponse($creditSession,
            "client_token",
            "purchase_country",
            "currency",
            "locale",
            "amount",
            "tax_amount",
            "products");

        foreach ($creditSession["products"] as $creditSessionProduct) {
            $this->assertResponse($creditSessionProduct,
                "name",
                "quantity",
                "unit_price",
                "total_amount");
        }

    }
}
