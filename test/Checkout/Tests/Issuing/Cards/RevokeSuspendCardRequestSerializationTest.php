<?php

namespace Checkout\Tests\Issuing\Cards;

use Checkout\Issuing\Cards\Revoke\RevokeCardRequest;
use Checkout\Issuing\Cards\Revoke\RevokeReason;
use Checkout\Issuing\Cards\Suspend\SuspendCardRequest;
use Checkout\Issuing\Cards\Suspend\SuspendReason;
use Checkout\JsonSerializer;
use PHPUnit\Framework\TestCase;

class RevokeSuspendCardRequestSerializationTest extends TestCase
{
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
     * The body is optional, so a request without a reason sends no reason key. The serializer
     * writes an empty request object as an empty JSON array.
     *
     * @test
     */
    public function shouldSendNoReasonForAnEmptyRequest()
    {
        $this->assertSame([], $this->serializer->deserialize($this->serializer->serialize(new RevokeCardRequest())));
        $this->assertSame([], $this->serializer->deserialize($this->serializer->serialize(new SuspendCardRequest())));
        $this->assertSame("[]", $this->serializer->serialize(new RevokeCardRequest()));
    }
}
