<?php

namespace Checkout\Tests\Events\Previous;

use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\PlatformType;
use Checkout\Tests\SandboxTestFixture;

class EventsIntegrationTest extends SandboxTestFixture
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
    public function shouldRetrieveDefaultEventTypes()
    {
        $response = $this->previousApi->getEventsClient()->retrieveAllEventTypes();
        $allEventTypes = $response["items"];
        $this->assertNotNull($allEventTypes);
        $this->assertTrue(sizeof($allEventTypes) == 2);
        $this->assertArrayHasKey("version", $allEventTypes[0]);
        $this->assertEquals("1.0", $allEventTypes[0]["version"]);
        $this->assertArrayHasKey("version", $allEventTypes[1]);
        $this->assertEquals("2.0", $allEventTypes[1]["version"]);

        $response1 = $this->previousApi->getEventsClient()->retrieveAllEventTypes($allEventTypes[0]["version"]);
        $versionOne = $response1["items"];
        $this->assertNotNull($versionOne);
        $this->assertTrue(sizeof($versionOne) == 1);
        $this->assertEquals("1.0", $versionOne[0]["version"]);
        $this->assertEquals(sizeof($allEventTypes[0]["event_types"]), sizeof($versionOne[0]["event_types"]));

        $response2 = $this->previousApi->getEventsClient()->retrieveAllEventTypes($allEventTypes[1]["version"]);
        $versionTwo = $response2["items"];
        $this->assertNotNull($versionTwo);
        $this->assertTrue(sizeof($versionTwo) == 1);
        $this->assertEquals("2.0", $versionTwo[0]["version"]);
        $this->assertEquals(sizeof($allEventTypes[1]["event_types"]), sizeof($versionTwo[0]["event_types"]));
    }

}
