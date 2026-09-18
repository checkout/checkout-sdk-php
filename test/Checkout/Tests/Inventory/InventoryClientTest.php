<?php

namespace Checkout\Tests\Inventory;

use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\Inventory\InventoryClient;
use Checkout\Inventory\Entities\InventoryConditionType;
use Checkout\Inventory\Entities\InventoryMoney;
use Checkout\Inventory\Entities\InventoryReservationItem;
use Checkout\Inventory\Requests\InventoryAdjustmentRequest;
use Checkout\Inventory\Requests\InventoryReservationRequest;
use Checkout\Inventory\Requests\InventorySetLevelsRequest;
use Checkout\Inventory\Requests\InventorySetProductRequest;
use Checkout\PlatformType;
use Checkout\Tests\UnitTestFixture;

class InventoryClientTest extends UnitTestFixture
{
    /**
     * @var InventoryClient
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
        $this->initMocks(PlatformType::$default_oauth);
        $this->client = new InventoryClient($this->apiClient, $this->configuration);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldAdjustInventory()
    {
        $expectedResponse = $this->buildExpectedInventoryLevelsResponse();

        $this->apiClient
            ->method("post")
            ->willReturn($expectedResponse);

        $request = new InventoryAdjustmentRequest();
        $request->variant_id = "var_123";
        $request->delta = -3;
        $request->reason = "damaged in warehouse";

        $response = $this->client->adjustInventory($request, "idem_key_123");

        $this->assertNotNull($response);
        $this->assertArrayHasKey("variant_id", $response);
        $this->assertEquals("var_123", $response["variant_id"]);
        $this->assertEquals("in_stock", $response["state"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreateInventoryReservation()
    {
        $expectedResponse = $this->buildExpectedReservationResponse();

        $this->apiClient
            ->method("post")
            ->willReturn($expectedResponse);

        $item = new InventoryReservationItem();
        $item->variant_id = "var_123";
        $item->quantity = 2;

        $request = new InventoryReservationRequest();
        $request->owner_type = "ucp_session";
        $request->owner_reference = "cs_8f42";
        $request->items = [$item];
        $request->ttl_seconds = 900;

        $response = $this->client->createInventoryReservation($request);

        $this->assertNotNull($response);
        $this->assertArrayHasKey("id", $response);
        $this->assertEquals("held", $response["state"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetInventoryReservation()
    {
        $expectedResponse = $this->buildExpectedReservationResponse();

        $this->apiClient
            ->method("get")
            ->willReturn($expectedResponse);

        $response = $this->client->getInventoryReservation("rsv_tkoi5db4hryu5cei5vwoabr7we");

        $this->assertNotNull($response);
        $this->assertEquals("rsv_tkoi5db4hryu5cei5vwoabr7we", $response["id"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCommitInventoryReservation()
    {
        $expectedResponse = array_merge($this->buildExpectedReservationResponse(), ["state" => "committed"]);

        $this->apiClient
            ->method("post")
            ->willReturn($expectedResponse);

        $response = $this->client->commitInventoryReservation("rsv_tkoi5db4hryu5cei5vwoabr7we");

        $this->assertNotNull($response);
        $this->assertEquals("committed", $response["state"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldReleaseInventoryReservation()
    {
        $expectedResponse = array_merge($this->buildExpectedReservationResponse(), ["state" => "released"]);

        $this->apiClient
            ->method("post")
            ->willReturn($expectedResponse);

        $response = $this->client->releaseInventoryReservation("rsv_tkoi5db4hryu5cei5vwoabr7we");

        $this->assertNotNull($response);
        $this->assertEquals("released", $response["state"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetInventoryLevels()
    {
        $expectedResponse = $this->buildExpectedInventoryLevelsResponse();

        $this->apiClient
            ->method("get")
            ->willReturn($expectedResponse);

        $response = $this->client->getInventoryLevels("var_123", "product");

        $this->assertNotNull($response);
        $this->assertEquals("var_123", $response["variant_id"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldSetInventoryLevels()
    {
        $expectedResponse = $this->buildExpectedInventoryLevelsResponse();

        $this->apiClient
            ->method("put")
            ->willReturn($expectedResponse);

        $request = new InventorySetLevelsRequest();
        $request->on_hand = 25;
        $request->safety_stock = 2;
        $request->reason = "stock take 2026-07";

        $response = $this->client->setInventoryLevels("var_123", $request);

        $this->assertNotNull($response);
        $this->assertEquals(25, $response["on_hand"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetInventoryProduct()
    {
        $expectedResponse = $this->buildExpectedProductKnowledgeResponse();

        $this->apiClient
            ->method("get")
            ->willReturn($expectedResponse);

        $response = $this->client->getInventoryProduct("var_123");

        $this->assertNotNull($response);
        $this->assertEquals("var_123", $response["variant_id"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldSetInventoryProduct()
    {
        $expectedResponse = $this->buildExpectedProductKnowledgeResponse();

        $this->apiClient
            ->method("put")
            ->willReturn($expectedResponse);

        $price = new InventoryMoney();
        $price->amount = 1999;
        $price->currency = "USD";

        $request = new InventorySetProductRequest();
        $request->title = "Classic leather belt, brown";
        $request->description = "A full-grain leather belt with a brushed nickel buckle.";
        $request->product_url = "https://merchant.example.com/products/classic-leather-belt-brown";
        $request->image_url = "https://merchant.example.com/images/belt-brown-main.jpg";
        $request->price = $price;
        $request->condition = InventoryConditionType::$new;

        $response = $this->client->setInventoryProduct("var_123", $request);

        $this->assertNotNull($response);
        $this->assertEquals("var_123", $response["variant_id"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldDeleteInventoryProduct()
    {
        $expectedResponse = [];

        $this->apiClient
            ->method("delete")
            ->willReturn($expectedResponse);

        $response = $this->client->deleteInventoryProduct("var_123");

        $this->assertNotNull($response);
        $this->assertEquals([], $response);
    }

    // Response builders for expected responses

    private function buildExpectedInventoryLevelsResponse()
    {
        return [
            "variant_id" => "var_123",
            "on_hand" => 25,
            "reserved" => 3,
            "safety_stock" => 2,
            "available" => 20,
            "state" => "in_stock",
            "source" => "managed",
            "created_on" => "2026-07-01T00:00:00Z",
            "modified_on" => "2026-07-01T00:00:00Z",
            "_links" => [
                "self" => ["href" => "https://api.checkout.com/inventory/var_123"],
                "set" => ["href" => "https://api.checkout.com/inventory/var_123"]
            ]
        ];
    }

    private function buildExpectedReservationResponse()
    {
        return [
            "id" => "rsv_tkoi5db4hryu5cei5vwoabr7we",
            "state" => "held",
            "owner_type" => "ucp_session",
            "owner_reference" => "cs_8f42",
            "items" => [
                ["variant_id" => "var_123", "quantity" => 2]
            ],
            "expires_at" => "2026-07-01T00:15:00Z",
            "created_on" => "2026-07-01T00:00:00Z",
            "_links" => [
                "self" => ["href" => "https://api.checkout.com/inventory/reservations/rsv_tkoi5db4hryu5cei5vwoabr7we"],
                "commit" => ["href" => "https://api.checkout.com/inventory/reservations/rsv_tkoi5db4hryu5cei5vwoabr7we/commit"],
                "release" => ["href" => "https://api.checkout.com/inventory/reservations/rsv_tkoi5db4hryu5cei5vwoabr7we/release"]
            ]
        ];
    }

    private function buildExpectedProductKnowledgeResponse()
    {
        return [
            "variant_id" => "var_123",
            "title" => "Classic leather belt, brown",
            "description" => "A full-grain leather belt with a brushed nickel buckle.",
            "product_url" => "https://merchant.example.com/products/classic-leather-belt-brown",
            "image_url" => "https://merchant.example.com/images/belt-brown-main.jpg",
            "price" => ["amount" => 1999, "currency" => "USD"],
            "condition" => "new",
            "created_on" => "2026-07-01T00:00:00Z",
            "modified_on" => "2026-07-01T00:00:00Z",
            "_links" => [
                "self" => ["href" => "https://api.checkout.com/inventory/var_123/product"],
                "set" => ["href" => "https://api.checkout.com/inventory/var_123/product"],
                "delete" => ["href" => "https://api.checkout.com/inventory/var_123/product"]
            ]
        ];
    }
}
