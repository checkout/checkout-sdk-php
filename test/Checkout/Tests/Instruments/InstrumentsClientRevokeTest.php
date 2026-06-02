<?php

namespace Checkout\Tests\Instruments;

use Checkout\CheckoutApiException;
use Checkout\PlatformType;
use Checkout\Tests\UnitTestFixture;
use Checkout\Instruments\InstrumentsClient;

class InstrumentsClientRevokeTest extends UnitTestFixture
{
    /**
     * @var InstrumentsClient
     */
    private $client;

    /**
     * @before
     */
    public function init()
    {
        $this->initMocks(PlatformType::$default);
        $this->client = new InstrumentsClient($this->apiClient, $this->configuration);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldRevokeInstrument()
    {
        $this->apiClient
            ->method("patch")
            ->willReturn(["success" => true]);

        $response = $this->client->revoke("instrument_id");
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldRevokeInstrumentWithId()
    {
        $expectedInstrumentId = "inst_test123";
        
        $this->apiClient
            ->method("patch")
            ->willReturn(["success" => true]);

        $response = $this->client->revoke($expectedInstrumentId);
        $this->assertNotNull($response);
    }
}
