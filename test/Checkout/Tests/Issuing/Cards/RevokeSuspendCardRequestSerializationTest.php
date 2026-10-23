<?php

namespace Checkout\Tests\Issuing\Cards;

use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutException;
use Checkout\Issuing\Cards\Revoke\RevokeCardRequest;
use Checkout\Issuing\Cards\Revoke\RevokeReason;
use Checkout\Issuing\Cards\Suspend\SuspendCardRequest;
use Checkout\Issuing\Cards\Suspend\SuspendReason;
use Checkout\JsonSerializer;
use PHPUnit\Framework\TestCase;

class RevokeSuspendCardRequestSerializationTest extends TestCase
{
    use StubbedHttpIssuingClientTrait;

    /**
     * @var JsonSerializer
     */
    private $serializer;

    /**
     * @before
     */
    public function init()
    {
        $this->serializer = new JsonSerializer();
    }

    /**
     * @test
     */
    public function shouldRoundtripEveryRevokeReason()
    {
        foreach ([RevokeReason::$expired, RevokeReason::$reported_lost, RevokeReason::$reported_stolen] as $reason) {
            $request = new RevokeCardRequest();
            $request->reason = $reason;

            $decoded = $this->serializer->deserialize($this->serializer->serialize($request));

            $this->assertSame(["reason" => $reason], $decoded);
        }
    }

    /**
     * @test
     */
    public function shouldMatchTheRevokeSwaggerExample()
    {
        $request = new RevokeCardRequest();
        $request->reason = RevokeReason::$reported_lost;

        $this->assertJsonStringEqualsJsonString('{"reason":"reported_lost"}', $this->serializer->serialize($request));
    }

    /**
     * @test
     */
    public function shouldRoundtripEverySuspendReason()
    {
        foreach ([SuspendReason::$suspected_lost, SuspendReason::$suspected_stolen] as $reason) {
            $request = new SuspendCardRequest();
            $request->reason = $reason;

            $decoded = $this->serializer->deserialize($this->serializer->serialize($request));

            $this->assertSame(["reason" => $reason], $decoded);
        }
    }

    /**
     * @test
     */
    public function shouldMatchTheSuspendSwaggerExample()
    {
        $request = new SuspendCardRequest();
        $request->reason = SuspendReason::$suspected_lost;

        $this->assertJsonStringEqualsJsonString('{"reason":"suspected_lost"}', $this->serializer->serialize($request));
    }

    /**
     * The body is optional. An empty request object would serialize to an empty JSON array,
     * which the API rejects, so revokeCard and suspendCard send no body for it, the same as a
     * null request.
     *
     * @test
     * @throws CheckoutApiException
     * @throws CheckoutArgumentException
     * @throws CheckoutException
     */
    public function shouldSendNoBodyForAnEmptyRequestObject()
    {
        $captured = [];
        $client = $this->buildStubbedClient(200, '{"_links":{}}', $captured);

        $client->revokeCard("crd_12345", new RevokeCardRequest());
        $client->suspendCard("crd_12345", new SuspendCardRequest());

        $this->assertCount(2, $captured);
        $this->assertStringEndsWith("issuing/cards/crd_12345/revoke", $captured[0]->getUri()->getPath());
        $this->assertSame("", (string) $captured[0]->getBody());
        $this->assertStringEndsWith("issuing/cards/crd_12345/suspend", $captured[1]->getUri()->getPath());
        $this->assertSame("", (string) $captured[1]->getBody());
    }
}
