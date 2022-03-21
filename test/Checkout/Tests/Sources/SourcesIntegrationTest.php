<?php

namespace Checkout\Tests\Sources;

use Checkout\PlatformType;
use Checkout\Sources\SepaSourceRequest;
use Checkout\Sources\SourceData;
use Checkout\Tests\SandboxTestFixture;

class SourcesIntegrationTest extends SandboxTestFixture
{

    /**
     * @before
     */
    public function before()
    {
        $this->init(PlatformType::$default);
    }

    /**
     * @test
     */
    public function shouldSepaSource()
    {
        $sourceData = new SourceData();
        $sourceData->first_name = "Marcus";
        $sourceData->last_name = "Barrilius Maximus";
        $sourceData->account_iban = "DE68100100101234567895";
        $sourceData->bic = "PBNKDEFFXXX";
        $sourceData->billing_descriptor = ".NET SDK test";
        $sourceData->mandate_type = "single";

        $sepaSourceRequest = new SepaSourceRequest();
        $sepaSourceRequest->billing_address = $this->getAddress();
        $sepaSourceRequest->phone = $this->getPhone();
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
