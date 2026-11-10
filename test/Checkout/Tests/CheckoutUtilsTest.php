<?php

namespace Checkout\Tests;

use Checkout\CheckoutArgumentException;
use Checkout\CheckoutUtils;
use DateTime;
use PHPUnit\Framework\TestCase;

class CheckoutUtilsTest extends TestCase
{

    /**
     * @test
     */
    public static function shouldMatchProjectVersion()
    {
        $normalizeDir = str_replace(__DIR__, '\\', '/');
        $path = str_replace($normalizeDir, "\lib\checkout", "version.json");
        $contentComposer = json_decode(file_get_contents($path), true);

        if (!array_key_exists("version", $contentComposer)) {
            self::fail();
        }

        self::assertEquals(CheckoutUtils::PROJECT_VERSION, $contentComposer["version"]);
    }

    /**
     * @test
     */
    public static function shouldFormatDateToIso8601()
    {
        $date = new DateTime();
        $formatted = CheckoutUtils::formatDate($date);
        self::assertEquals($date->format("Y-m-d\TH:i:sO"), $formatted);
    }

    /**
     * @test
     */
    public function shouldBuildQueryWithEachValueEncoded()
    {
        $query = CheckoutUtils::buildQuery([
            "statuses" => "a&entity_ids=x#b",
            "reference" => "ref 1/2?+~",
            "skip" => 0,
            "limit" => 1.5,
            "flag" => false,
            "on" => true,
            "empty" => "",
            "missing" => null,
            "from" => new DateTime("2026-01-01T00:00:00+02:00"),
        ]);

        $this->assertSame(
            "statuses=a%26entity_ids%3Dx%23b&reference=ref%201%2F2%3F%2B~&skip=0&limit=1.5&flag=false&on=true"
            . "&empty=&from=2026-01-01T00%3A00%3A00%2B0200",
            $query
        );
    }

    /**
     * @test
     */
    public function shouldBuildEmptyQueryWhenNoValueIsSet()
    {
        $this->assertSame("", CheckoutUtils::buildQuery([]));
        $this->assertSame("", CheckoutUtils::buildQuery(["a" => null]));
    }

    /**
     * @return array[]
     */
    public static function invalidQueryValues()
    {
        return [
            "array" => [["a", "b"]],
            "object" => [new \stdClass()],
            "immutable date" => [new \DateTimeImmutable("2026-01-01T00:00:00+00:00")],
        ];
    }

    /**
     * @test
     * @dataProvider invalidQueryValues
     * @param mixed $value
     */
    public function shouldRejectQueryValueThatIsNotScalar($value)
    {
        $this->expectException(CheckoutArgumentException::class);

        CheckoutUtils::buildQuery(["a" => $value]);
    }
}
