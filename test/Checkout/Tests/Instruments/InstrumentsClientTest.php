<?php

namespace Checkout\Tests\Instruments;

use Checkout\CheckoutApiException;
use Checkout\Instruments\CreateInstrumentRequest;
use Checkout\Instruments\InstrumentsClient;
use Checkout\Instruments\UpdateInstrumentRequest;
use Checkout\PlatformType;
use Checkout\Tests\UnitTestFixture;

class InstrumentsClientTest extends UnitTestFixture
{
    private InstrumentsClient $client;

    /**
     * @before
     */
    public function init(): void
    {
        $this->initMocks(PlatformType::$default);
        $this->client = new InstrumentsClient($this->apiClient, $this->configuration);
    }


    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreateInstrument(): void
    {

        $this->apiClient
            ->method("post")
            ->willReturn("foo");

        $response = $this->client->create(new CreateInstrumentRequest());
        $this->assertNotNull($response);
    }


    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetInstrument(): void
    {
        $this->apiClient->method("get")
            ->willReturn("foo");


        $response = $this->client->get("instrument_id");
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldUpdateInstrument(): void
    {
        $this->apiClient->method("patch")
            ->willReturn("foo");


        $response = $this->client->update("instrument_id", new UpdateInstrumentRequest());
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @doesNotPerformAssertions
     * @throws CheckoutApiException
     */
    public function shouldDeleteInstruments(): void
    {
        $this->apiClient->method("delete");

        $this->client->delete("instrument_id");
    }
}
