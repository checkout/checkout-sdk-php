<?php

namespace Checkout\Tests\Issuing\Controls;

use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\Issuing\IssuingClient;
use Checkout\Issuing\Controls\Create\VelocityCardControlRequest;
use Checkout\Issuing\Controls\Query\CardControlsQuery;
use Checkout\Issuing\Controls\Update\UpdateCardControlRequest;
use Checkout\PlatformType;
use Checkout\Tests\UnitTestFixture;

class ControlsClientTest extends UnitTestFixture
{
    /**
     * @var IssuingClient
     */
    private $client;

    /**
     * @before
     * @throws CheckoutAuthorizationException
     * @throws CheckoutArgumentException
     * @throws CheckoutException
     */
    public function init()
    {
        $this->initMocks(PlatformType::$default);
        $this->client = new IssuingClient($this->apiClient, $this->configuration);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreateControl()
    {
        $this->apiClient
            ->method("post")
            ->willReturn(["id" => "ctl_12345"]);

        $request = new VelocityCardControlRequest();
        $response = $this->client->createControl($request);

        $this->assertNotNull($response);
        $this->assertArrayHasKey("id", $response);
        $this->assertEquals("ctl_12345", $response["id"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetCardControls()
    {
        $this->apiClient
            ->method("query")
            ->willReturn([
                "controls" => [
                    ["id" => "ctl_12345", "target_id" => "crd_test"]
                ]
            ]);

        $query = new CardControlsQuery();
        $response = $this->client->getCardControls($query);

        $this->assertNotNull($response);
        $this->assertArrayHasKey("controls", $response);
        $this->assertCount(1, $response["controls"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetControlDetails()
    {
        $this->apiClient
            ->method("get")
            ->willReturn([
                "id" => "ctl_12345",
                "description" => "Test control"
            ]);

        $response = $this->client->getCardControlDetails("ctl_12345");

        $this->assertNotNull($response);
        $this->assertArrayHasKey("id", $response);
        $this->assertEquals("ctl_12345", $response["id"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldUpdateControl()
    {
        $this->apiClient
            ->method("put")
            ->willReturn(["id" => "ctl_12345"]);

        $request = new UpdateCardControlRequest();
        $response = $this->client->updateCardControl("ctl_12345", $request);

        $this->assertNotNull($response);
        $this->assertArrayHasKey("id", $response);
        $this->assertEquals("ctl_12345", $response["id"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldRemoveControl()
    {
        $this->apiClient
            ->method("delete")
            ->willReturn(["id" => "ctl_12345"]);

        $response = $this->client->removeCardControl("ctl_12345");

        $this->assertNotNull($response);
        $this->assertArrayHasKey("id", $response);
        $this->assertEquals("ctl_12345", $response["id"]);
    }
}
