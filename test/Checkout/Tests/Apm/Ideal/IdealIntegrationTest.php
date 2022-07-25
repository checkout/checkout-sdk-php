<?php

namespace Checkout\Tests\Apm\Ideal;

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
        $this->init(PlatformType::$default);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetInfo()
    {
        $this->markTestSkipped("unavailable");
        $response = $this->checkoutApi->getIdealClient()->getInfo();
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
        $response = $this->checkoutApi->getIdealClient()->getIssuers();
        $this->assertResponse(
            $response,
            "countries",
            "_links",
            "_links.self"
        );
    }
}
