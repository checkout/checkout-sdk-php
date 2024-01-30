<?php

namespace Checkout\Tests\Forex;

use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\Common\Currency;
use Checkout\Forex\ForexSource;
use Checkout\Forex\QuoteRequest;
use Checkout\Forex\RatesQueryFilter;
use Checkout\PlatformType;
use Checkout\Tests\SandboxTestFixture;

class ForexIntegrationTest extends SandboxTestFixture
{

    /**
     * @before
     * @throws CheckoutAuthorizationException
     * @throws CheckoutArgumentException
     * @throws CheckoutException
     */
    public function before()
    {
        $this->init(PlatformType::$default_oauth);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldRequestQuote()
    {
        $this->markTestSkipped("unstable");

        $quoteRequest = new QuoteRequest();
        $quoteRequest->source_currency = Currency::$GBP;
        $quoteRequest->source_amount = 30000;
        $quoteRequest->destination_currency = Currency::$USD;
        $quoteRequest->process_channel_id = "pc_abcdefghijklmnopqrstuvwxyz";

        $quoteResponse = $this->checkoutApi->getForexClient()->requestQuote($quoteRequest);
        $this->assertResponse(
            $quoteResponse,
            "id",
            "source_currency",
            "source_amount",
            "destination_currency",
            "destination_amount",
            "rate",
            "expires_on"
        );
        $this->assertEquals($quoteRequest->source_currency, $quoteResponse["source_currency"]);
        $this->assertEquals($quoteRequest->source_amount, $quoteResponse["source_amount"]);
        $this->assertEquals($quoteRequest->destination_currency, $quoteResponse["destination_currency"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetRates()
    {
        $this->markTestSkipped("processing_channel_id");

        $ratesQuery = new RatesQueryFilter();
        $ratesQuery->product = "card_payouts";
        $ratesQuery->source = ForexSource::$VISA;
        $ratesQuery->currency_pairs = "GBPEUR,USDNOK,JPNCAD";
        $ratesQuery->process_channel_id = "pc_zs5fqhybzc2e3jmq3efvybybpq";

        $ratesResponse = $this->checkoutApi->getForexClient()->getRates($ratesQuery);
        $this->assertResponse(
            $ratesResponse,
            "product",
            "source",
            "rates"
        );
        $this->assertEquals($ratesQuery->product, $ratesResponse["product"]);
        $this->assertEquals($ratesQuery->source, $ratesResponse["source"]);
    }
}
