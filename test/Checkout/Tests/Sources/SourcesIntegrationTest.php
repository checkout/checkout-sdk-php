<?php

namespace Checkout\Tests\Sources;

use Checkout\Common\Address;
use Checkout\Common\Country;
use Checkout\Common\Phone;
use Checkout\PlatformType;
use Checkout\Sources\SepaSourceRequest;
use Checkout\Sources\SourceData;
use Checkout\Tests\SandboxTestFixture;

class SourcesIntegrationTest extends SandboxTestFixture
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
     */
    public function shouldSepaSource(): void
    {

        $phone = new Phone();
        $phone->country_code = "44";
        $phone->number = "020 222333";

        $address = new Address();
        $address->address_line1 = "CheckoutSdk.com";
        $address->address_line2 = "90 Tottenham Court Road";
        $address->city = "London";
        $address->state = "London";
        $address->zip = "W1T 4TJ";
        $address->country = Country::$GB;

        $sourceData = new SourceData();
        $sourceData->first_name = "Marcus";
        $sourceData->last_name = "Barrilius Maximus";
        $sourceData->account_iban = "DE68100100101234567895";
        $sourceData->bic = "PBNKDEFFXXX";
        $sourceData->billing_descriptor = ".NET SDK test";
        $sourceData->mandate_type = "single";

        $sepaSourceRequest = new SepaSourceRequest();
        $sepaSourceRequest->billing_address = $address;
        $sepaSourceRequest->phone = $phone;
        $sepaSourceRequest->reference = ".NET SDK test";
        $sepaSourceRequest->source_data = $sourceData;

        $this->assertResponse($this->defaultApi->getSourcesClient()->createSepaSource($sepaSourceRequest),
            "type",
            "customer.id",
            "id",
            "_links.sepa:mandate-cancel",
            "_links.sepa:mandate-get",
            "response_code",
            "response_data.mandate_reference"
        );
    }

}
