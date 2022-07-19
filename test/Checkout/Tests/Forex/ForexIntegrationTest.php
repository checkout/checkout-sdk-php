<?php

namespace Checkout\Tests\Forex;

use Checkout\CheckoutApiException;
use Checkout\Common\Currency;
use Checkout\Forex\QuoteRequest;
use Checkout\PlatformType;
use Checkout\Tests\SandboxTestFixture;

class ForexIntegrationTest extends SandboxTestFixture
{

    /**
     * @before
     */
    public function before()
    {
        $this->init(PlatformType::$fourOAuth);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldRequestQuote()
    {
        $quoteRequest = new QuoteRequest();
        $quoteRequest->source_currency = Currency::$GBP;
        $quoteRequest->source_amount = 30000;
        $quoteRequest->destination_currency = Currency::$USD;
        $quoteRequest->process_channel_id = "pc_abcdefghijklmnopqrstuvwxyz";

        $quoteResponse = $this->fourApi->getForexClient()->requestQuote($quoteRequest);
        $this->assertResponse($quoteResponse, "id",
            "source_currency",
            "source_amount",
            "destination_currency",
            "destination_amount",
            "rate",
            "expires_on");
        $this->assertEquals($quoteRequest->source_currency, $quoteResponse["source_currency"]);
        $this->assertEquals($quoteRequest->source_amount, $quoteResponse["source_amount"]);
        $this->assertEquals($quoteRequest->destination_currency, $quoteResponse["destination_currency"]);
    }
}