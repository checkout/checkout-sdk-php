<?php

namespace Checkout\Tests;

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

}
