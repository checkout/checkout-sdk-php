<?php

namespace Checkout\Tests\Events;

use Checkout\PlatformType;
use Checkout\Tests\SandboxTestFixture;
use function PHPUnit\Framework\assertArrayHasKey;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertNotNull;
use function PHPUnit\Framework\assertTrue;

class EventsIntegrationTest extends SandboxTestFixture
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
    public function shouldRetrieveDefaultEventTypes(): void
    {
        $allEventTypes = $this->defaultApi->getEventsClient()->retrieveAllEventTypes();
        assertNotNull($allEventTypes);
        assertTrue(sizeof($allEventTypes) == 2);
        assertArrayHasKey("version", $allEventTypes[0]);
        assertEquals("1.0", $allEventTypes[0]["version"]);
        assertArrayHasKey("version", $allEventTypes[1]);
        assertEquals("2.0", $allEventTypes[1]["version"]);

        $versionOne = $this->defaultApi->getEventsClient()->retrieveAllEventTypes($allEventTypes[0]["version"]);
        assertNotNull($versionOne);
        assertTrue(sizeof($versionOne) == 1);
        assertEquals("1.0", $versionOne[0]["version"]);
        assertEquals(sizeof($allEventTypes[0]["event_types"]), sizeof($versionOne[0]["event_types"]));

        $versionTwo = $this->defaultApi->getEventsClient()->retrieveAllEventTypes($allEventTypes[1]["version"]);
        assertNotNull($versionTwo);
        assertTrue(sizeof($versionTwo) == 1);
        assertEquals("2.0", $versionTwo[0]["version"]);
        assertEquals(sizeof($allEventTypes[1]["event_types"]), sizeof($versionTwo[0]["event_types"]));
    }

}
