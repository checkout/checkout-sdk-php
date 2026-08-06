<?php

namespace Checkout\Tests\Sessions;

use Checkout\Common\ChallengeIndicatorType;
use Checkout\JsonSerializer;
use Checkout\Payments\ThreeDsRequest;
use Checkout\Sessions\SessionChallengeIndicatorType;
use Checkout\Sessions\SessionRequest;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * Covers the two challenge-indicator value classes and their call sites: the nine-value sessions
 * class used by POST /sessions, and the four-value shared class used by the payments 3ds field.
 */
class ChallengeIndicatorSerializationTest extends TestCase
{
    /**
     * The nine values accepted by SessionRequest::$challenge_indicator, per the API Reference
     * ChallengeIndicator schema.
     */
    private static function sessionValues()
    {
        return [
            "no_preference",
            "no_challenge_requested",
            "challenge_requested",
            "challenge_requested_mandate",
            "low_value",
            "trusted_listing",
            "trusted_listing_prompt",
            "transaction_risk_assessment",
            "data_share",
        ];
    }

    /**
     * The four values accepted by the 3ds.challenge_indicator field on payments, hosted payments,
     * payment links and payment sessions.
     */
    private static function paymentValues()
    {
        return [
            "no_preference",
            "no_challenge_requested",
            "challenge_requested",
            "challenge_requested_mandate",
        ];
    }

    public function testSessionClassExposesAllNineValues()
    {
        $declared = (new ReflectionClass(SessionChallengeIndicatorType::class))->getStaticProperties();

        $this->assertCount(9, $declared);
        $this->assertSame(self::sessionValues(), array_values($declared));
    }

    public function testEveryValueIsNamedAfterItsWireValue()
    {
        $declared = (new ReflectionClass(SessionChallengeIndicatorType::class))->getStaticProperties();

        foreach ($declared as $name => $value) {
            $this->assertSame($name, $value, "property \$$name must hold the wire value \"$name\"");
        }
    }

    public function testEveryValueSerializesOnSessionRequest()
    {
        foreach (self::sessionValues() as $value) {
            $request = new SessionRequest();
            $request->challenge_indicator = $value;

            $decoded = json_decode((new JsonSerializer())->serialize($request), true);

            $this->assertSame($value, $decoded['challenge_indicator']);
        }
    }

    public function testSessionRequestDefaultsToNoPreference()
    {
        $request = new SessionRequest();

        $this->assertSame(
            SessionChallengeIndicatorType::$no_preference,
            $request->challenge_indicator
        );

        $decoded = json_decode((new JsonSerializer())->serialize($request), true);
        $this->assertSame("no_preference", $decoded['challenge_indicator']);
    }

    public function testEveryPaymentValueSerializesOnThreeDsRequest()
    {
        foreach (self::paymentValues() as $value) {
            $request = new ThreeDsRequest();
            $request->challenge_indicator = $value;

            $decoded = json_decode((new JsonSerializer())->serialize($request), true);

            $this->assertSame($value, $decoded['challenge_indicator']);
        }
    }

    /**
     * The shared class keeps all nine values for backwards compatibility, but the five exemption
     * values are deprecated on it because the payments 3ds field rejects them. Removing them would
     * break merchants already referencing them, so this asserts they are still present and still
     * carry a @deprecated tag.
     */
    public function testSharedClassKeepsButDeprecatesTheFiveExemptionValues()
    {
        $reflection = new ReflectionClass(ChallengeIndicatorType::class);
        $exemptions = [
            "low_value",
            "trusted_listing",
            "trusted_listing_prompt",
            "transaction_risk_assessment",
            "data_share",
        ];

        foreach ($exemptions as $name) {
            $this->assertTrue(
                $reflection->hasProperty($name),
                "\$$name must be kept on the shared class for backwards compatibility"
            );
            $this->assertStringContainsString(
                "@deprecated",
                $reflection->getProperty($name)->getDocComment(),
                "\$$name must be marked @deprecated on the shared class"
            );
        }

        foreach (self::paymentValues() as $name) {
            $this->assertStringNotContainsString(
                "@deprecated",
                $reflection->getProperty($name)->getDocComment(),
                "\$$name is valid for payments and must not be deprecated"
            );
        }
    }
}
