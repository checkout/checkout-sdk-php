<?php

namespace Checkout\Tests\Accounts;

use Checkout\Accounts\DateOfIncorporation;
use Checkout\JsonSerializer;
use PHPUnit\Framework\TestCase;

class DateOfIncorporationSerializationTest extends TestCase
{
    public function testDateOfIncorporationRoundTrip()
    {
        $dateOfIncorporation = new DateOfIncorporation();
        $dateOfIncorporation->day = 1;
        $dateOfIncorporation->month = 6;
        $dateOfIncorporation->year = 2010;

        $decoded = json_decode((new JsonSerializer())->serialize($dateOfIncorporation), true);

        $this->assertSame(1, $decoded['day']);
        $this->assertSame(6, $decoded['month']);
        $this->assertSame(2010, $decoded['year']);
    }

    public function testDateOfIncorporationOmitsNullDay()
    {
        $dateOfIncorporation = new DateOfIncorporation();
        $dateOfIncorporation->month = 6;
        $dateOfIncorporation->year = 2010;

        $decoded = json_decode((new JsonSerializer())->serialize($dateOfIncorporation), true);

        $this->assertArrayNotHasKey('day', $decoded);
        $this->assertSame(6, $decoded['month']);
        $this->assertSame(2010, $decoded['year']);
    }
}
