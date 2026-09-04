<?php

namespace Checkout\Tests\Apm\Bacs;

use Checkout\Apm\Bacs\BacsClient;
use Checkout\Apm\Bacs\BacsNotificationRequest;
use Checkout\CheckoutApiException;
use Checkout\PlatformType;
use Checkout\Tests\UnitTestFixture;

class BacsClientTest extends UnitTestFixture
{
    /**
     * @var BacsClient
     */
    private $client;

    /**
     * @before
     */
    public function init()
    {
        $this->initMocks(PlatformType::$default);
        $this->client = new BacsClient($this->apiClient, $this->configuration);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldSendNotification()
    {
        $this->apiClient
            ->expects($this->once())
            ->method("post")
            ->with(
                "apms/bacs/notifications",
                $this->isInstanceOf(BacsNotificationRequest::class),
                $this->anything()
            )
            ->willReturn(["event_id" => "evt_lzr4csdtddwetactr6phd3kea4"]);

        $response = $this->client->sendNotification(new BacsNotificationRequest());

        $this->assertNotNull($response);
        $this->assertEquals("evt_lzr4csdtddwetactr6phd3kea4", $response["event_id"]);
    }
}
