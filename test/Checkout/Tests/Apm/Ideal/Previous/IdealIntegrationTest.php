<?php

namespace Checkout\Tests\Apm\Ideal\Previous;

use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\PlatformType;
use Checkout\Tests\SandboxTestFixture;

class IdealIntegrationTest extends SandboxTestFixture
{

    /**
     * @before
     * @throws CheckoutAuthorizationException
     * @throws CheckoutArgumentException
     * @throws CheckoutException
     */
    public function before()
    {
        $this->init(PlatformType::$previous);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetInfo()
    {
        $this->markTestSkipped("unavailable");
        $response = $this->previousApi->getIdealClient()->getInfo();
        $this->assertResponse(
            $response,
            "_links",
            "_links.self",
            "_links.ideal:issuers",
            "_links.curies"
        );
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetIssuers()
    {
        $response = $this->previousApi->getIdealClient()->getIssuers();
        $this->assertResponse(
            $response,
            "countries",
            "_links",
            "_links.self"
        );
    }
}
