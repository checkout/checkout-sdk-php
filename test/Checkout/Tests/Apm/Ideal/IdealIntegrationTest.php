<?php

namespace Checkout\Tests\Apm\Ideal;

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
        $this->init(PlatformType::$default);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetInfo()
    {
        $response = $this->defaultApi->getIdealClient()->getInfo();
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
        $response = $this->defaultApi->getIdealClient()->getIssuers();
        $this->assertResponse($response,
            "countries",
            "_links",
            "_links.self");
    }
}
