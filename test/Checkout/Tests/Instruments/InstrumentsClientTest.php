<?php

namespace Checkout\Tests\Instruments;

use Checkout\Instruments\CreateInstrumentRequest;
use Checkout\Instruments\InstrumentsClient;
use Checkout\Instruments\UpdateInstrumentRequest;
use Checkout\PlatformType;
use Checkout\Tests\UnitTestFixture;

class InstrumentsClientTest extends UnitTestFixture
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
     */
    public function shouldCreateInstrument()
    {

        $this->apiClient
            ->method("post")
            ->willReturn("foo");

        $response = $this->client->create(new CreateInstrumentRequest());
        $this->assertNotNull($response);
    }


    /**
     * @test
     */
    public function shouldGetInstrument()
    {
        $this->apiClient->method("get")
            ->willReturn("foo");


        $response = $this->client->get("instrument_id");
        $this->assertNotNull($response);
    }

    /**
     * @test
     */
    public function shouldUpdateInstrument()
    {
        $this->apiClient->method("patch")
            ->willReturn("foo");


        $response = $this->client->update("instrument_id", new UpdateInstrumentRequest());
        $this->assertNotNull($response);
    }

    /**
     * @test
     */
    public function shouldDeleteInstruments()
    {
        $this->apiClient->method("delete")
            ->willReturn("foo");

        $response = $this->client->delete("instrument_id");
        $this->assertNotNull($response);
    }
}