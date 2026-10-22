<?php

namespace Checkout\Tests\Issuing\Cards;

use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\Issuing\Cards\Create\VirtualCardRequest;
use Checkout\Issuing\Cards\Credentials\CardCredentialsQuery;
use Checkout\Issuing\Cards\Revoke\RevokeCardRequest;
use Checkout\Issuing\Cards\Revoke\RevokeReason;
use Checkout\Issuing\Cards\Suspend\SuspendCardRequest;
use Checkout\Issuing\Cards\Suspend\SuspendReason;
use Checkout\Issuing\Cards\Update\UpdateCardRequest;
use Checkout\Issuing\IssuingClient;
use Checkout\PlatformType;
use Checkout\Tests\UnitTestFixture;

class CardsClientTest extends UnitTestFixture
{
    use StubbedHttpIssuingClientTrait;

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
    public function shouldCreateCard()
    {
        $this->apiClient
            ->method("post")
            ->willReturn(["id" => "crd_12345"]);

        $request = new VirtualCardRequest();
        $response = $this->client->createCard($request);

        $this->assertNotNull($response);
        $this->assertArrayHasKey("id", $response);
        $this->assertEquals("crd_12345", $response["id"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreateCardWithScheduledRevocationDate()
    {
        $this->apiClient
            ->method("post")
            ->willReturn([
                "id" => "crd_12345",
                "scheduled_revocation_date" => "2027-03-12",
                "last_activated_on" => null
            ]);

        $request = new VirtualCardRequest();
        $request->scheduled_revocation_date = "2027-03-12";

        $response = $this->client->createCard($request);

        $this->assertEquals("2027-03-12", $request->scheduled_revocation_date);
        $this->assertNotNull($response);
        $this->assertEquals("2027-03-12", $response["scheduled_revocation_date"]);
        $this->assertNull($response["last_activated_on"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldUpdateCardWithStatusAndScheduledRevocationDate()
    {
        $this->apiClient
            ->method("patch")
            ->willReturn([
                "id" => "crd_12345",
                "status" => "active",
                "scheduled_revocation_date" => "2027-03-12",
                "last_modified_date" => "2026-09-17T10:00:00Z"
            ]);

        $request = new UpdateCardRequest();
        $request->status = "active";
        $request->scheduled_revocation_date = "2027-03-12";

        $response = $this->client->updateCardDetails("crd_12345", $request);

        $this->assertEquals("active", $request->status);
        $this->assertEquals("2027-03-12", $request->scheduled_revocation_date);
        $this->assertNotNull($response);
        $this->assertEquals("active", $response["status"]);
        $this->assertEquals("2027-03-12", $response["scheduled_revocation_date"]);
        $this->assertArrayNotHasKey("encrypted_cvv", $response);
    }

    /**
     * update-card-response uses a virtual/physical discriminator; the virtual variant adds
     * is_single_use.
     *
     * @test
     * @throws CheckoutApiException
     */
    public function shouldUpdateVirtualCardWithIsSingleUse()
    {
        $this->apiClient
            ->method("patch")
            ->willReturn([
                "type" => "virtual",
                "last_modified_date" => "2026-09-17T10:00:00Z",
                "is_single_use" => true
            ]);

        $request = new UpdateCardRequest();
        $response = $this->client->updateCardDetails("crd_12345", $request);

        $this->assertNotNull($response);
        $this->assertTrue($response["is_single_use"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldActivateCardWithLastActivatedOn()
    {
        $this->apiClient
            ->method("post")
            ->willReturn([
                "id" => "crd_12345",
                "last_activated_on" => "2026-09-17T10:00:00Z"
            ]);

        $response = $this->client->activateCard("crd_12345");

        $this->assertNotNull($response);
        $this->assertArrayHasKey("last_activated_on", $response);
        $this->assertEquals("2026-09-17T10:00:00Z", $response["last_activated_on"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetCard()
    {
        $this->apiClient
            ->method("get")
            ->willReturn([
                "id" => "crd_12345",
                "display_name" => "Test Card"
            ]);

        $response = $this->client->getCardDetails("crd_12345");

        $this->assertNotNull($response);
        $this->assertArrayHasKey("id", $response);
        $this->assertEquals("crd_12345", $response["id"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetCardCredentials()
    {
        $this->apiClient
            ->method("query")
            ->willReturn([
                "number" => "4242424242424242",
                "cvc2" => "100"
            ]);

        $query = new CardCredentialsQuery();
        $response = $this->client->getCardCredentials("crd_12345", $query);

        $this->assertNotNull($response);
        $this->assertArrayHasKey("number", $response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldSuspendCard()
    {
        $this->apiClient
            ->method("post")
            ->willReturn(["id" => "crd_12345"]);

        $request = new SuspendCardRequest();
        $response = $this->client->suspendCard("crd_12345", $request);

        $this->assertNotNull($response);
        $this->assertArrayHasKey("id", $response);
        $this->assertEquals("crd_12345", $response["id"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldRevokeCardWithoutRequestBody()
    {
        $this->apiClient
            ->expects($this->once())
            ->method("post")
            ->with($this->equalTo("issuing/cards/crd_12345/revoke"), $this->isNull())
            ->willReturn(["_links" => []]);

        $this->assertNotNull($this->client->revokeCard("crd_12345"));
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldSuspendCardWithoutRequestBody()
    {
        $this->apiClient
            ->expects($this->once())
            ->method("post")
            ->with($this->equalTo("issuing/cards/crd_12345/suspend"), $this->isNull())
            ->willReturn(["_links" => []]);

        $this->assertNotNull($this->client->suspendCard("crd_12345", null));
    }

    /**
     * With no request the real ApiClient sends a POST with an empty body.
     *
     * @test
     * @throws CheckoutApiException
     * @throws CheckoutArgumentException
     * @throws CheckoutException
     */
    public function shouldSendNoBodyOnTheWireWhenRevokeAndSuspendHaveNoRequest()
    {
        $captured = [];
        $client = $this->buildStubbedClient(200, '{"_links":{}}', $captured);

        $client->revokeCard("crd_12345");
        $client->suspendCard("crd_12345");

        $this->assertCount(2, $captured);
        $this->assertSame("POST", $captured[0]->getMethod());
        $this->assertStringEndsWith("issuing/cards/crd_12345/revoke", $captured[0]->getUri()->getPath());
        $this->assertSame("", (string) $captured[0]->getBody());
        $this->assertStringEndsWith("issuing/cards/crd_12345/suspend", $captured[1]->getUri()->getPath());
        $this->assertSame("", (string) $captured[1]->getBody());
    }

    /**
     * @test
     * @throws CheckoutApiException
     * @throws CheckoutArgumentException
     * @throws CheckoutException
     */
    public function shouldSendTheReasonOnTheWireWhenRevokeAndSuspendHaveARequest()
    {
        $captured = [];
        $client = $this->buildStubbedClient(200, '{"_links":{}}', $captured);

        $revoke = new RevokeCardRequest();
        $revoke->reason = RevokeReason::$reported_lost;
        $suspend = new SuspendCardRequest();
        $suspend->reason = SuspendReason::$suspected_lost;

        $client->revokeCard("crd_12345", $revoke);
        $client->suspendCard("crd_12345", $suspend);

        $this->assertSame(["reason" => "reported_lost"], json_decode((string) $captured[0]->getBody(), true));
        $this->assertSame(["reason" => "suspected_lost"], json_decode((string) $captured[1]->getBody(), true));
    }
}
