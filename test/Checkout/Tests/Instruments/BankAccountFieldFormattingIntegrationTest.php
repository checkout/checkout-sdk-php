<?php

namespace Checkout\Tests\Instruments;

use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\Common\Country;
use Checkout\Common\Currency;
use Checkout\Common\AccountHolderType;
use Checkout\Instruments\Get\BankAccountFieldQuery;
use Checkout\Instruments\Get\PaymentNetwork;
use Checkout\PlatformType;
use Checkout\Tests\SandboxTestFixture;

class BankAccountFieldFormattingIntegrationTest extends SandboxTestFixture
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
    public function shouldFailGetBankAccountFieldFormattingWhenNoOAuthIsProvided()
    {
        $this->markTestSkipped("unavailable");
        $request = new BankAccountFieldQuery();
        $request->account_holder_type = AccountHolderType::$individual;
        $request->payment_network = PaymentNetwork::$local;

        $response = $this->checkoutApi->getInstrumentsClient()->getBankAccountFieldFormatting(Country::$GB, Currency::$GBP, $request);

        $this->assertResponse($response, "sections");

        foreach ($response["sections"] as $section) {
            $this->assertResponse($section, "name", "fields");
            $this->assertNotEmpty($section["fields"]);

            foreach ($section["fields"] as $field) {
                $this->assertResponse($field, "id", "display", "type");
            }
        }
    }
}
