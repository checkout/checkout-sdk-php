<?php

namespace Checkout\Tests\Apm\Ideal\Four;

use Checkout\CheckoutApiException;
use Checkout\PlatformType;
use Checkout\Tests\SandboxTestFixture;

class IdealIntegrationTest extends SandboxTestFixture
{

    /**
     * @before
     */
    public function before()
    {
        $this->init(PlatformType::$four);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetInfo()
    {
        $this->markTestSkipped("unavailable");
        $response = $this->fourApi->getIdealClient()->getInfo();
        $this->assertResponse($response,
            "_links",
            "_links.self",
            "_links.ideal:issuers",
            "_links.curies");
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetIssuers()
    {
        $response = $this->fourApi->getIdealClient()->getIssuers();
        $this->assertResponse($response,
            "countries",
            "_links",
            "_links.self");
    }
}
