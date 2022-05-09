<?php

namespace Checkout\Tests\Instruments\Four;

use Checkout\CheckoutApiException;
use Checkout\CheckoutAuthorizationException;
use Checkout\Common\Country;
use Checkout\Common\Currency;
use Checkout\Common\Four\AccountHolderType;
use Checkout\Instruments\Four\Get\BankAccountFieldQuery;
use Checkout\Instruments\Four\Get\PaymentNetwork;
use Checkout\PlatformType;
use Checkout\Tests\SandboxTestFixture;

class BankAccountFieldFormattingIntegrationTest extends SandboxTestFixture
{

    /**
     * @before
     * @throws CheckoutAuthorizationException
     * @throws \Checkout\CheckoutArgumentException
     * @throws \Checkout\CheckoutException
     */
    public function before()
    {
        $this->init(PlatformType::$fourOAuth);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldFailGetBankAccountFieldFormattingWhenNoOAuthIsProvided()
    {
        $request = new BankAccountFieldQuery();
        $request->account_holder_type = AccountHolderType::$individual;
        $request->payment_network = PaymentNetwork::$local;

        $response = $this->fourApi->getInstrumentsClient()->getBankAccountFieldFormatting(Country::$GB, Currency::$GBP, $request);

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
