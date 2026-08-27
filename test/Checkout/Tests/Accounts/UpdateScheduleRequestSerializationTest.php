<?php

namespace Checkout\Tests\Accounts;

use Checkout\Accounts\DaySchedule;
use Checkout\Accounts\ScheduleFrequencyWeeklyRequest;
use Checkout\Accounts\UpdateScheduleRequest;
use Checkout\JsonSerializer;
use PHPUnit\Framework\TestCase;

class UpdateScheduleRequestSerializationTest extends TestCase
{
    public function testSerializesIsvFieldsWhenSet()
    {
        $recurrence = new ScheduleFrequencyWeeklyRequest();
        $recurrence->by_day = [DaySchedule::$MONDAY];

        $request = new UpdateScheduleRequest();
        $request->enabled = true;
        $request->threshold = 100;
        $request->balance_minimum = 500;
        $request->carry_forward_enabled = true;
        $request->payment_instrument_id = "ppi_w4jelhppmfiufdnatam37wrfc4";
        $request->recurrence = $recurrence;

        $decoded = json_decode((new JsonSerializer())->serialize($request), true);

        $this->assertTrue($decoded['enabled']);
        $this->assertSame(100, $decoded['threshold']);
        $this->assertSame(500, $decoded['balance_minimum']);
        $this->assertTrue($decoded['carry_forward_enabled']);
        $this->assertSame("ppi_w4jelhppmfiufdnatam37wrfc4", $decoded['payment_instrument_id']);
        $this->assertSame("weekly", $decoded['recurrence']['frequency']);
        $this->assertSame(["monday"], $decoded['recurrence']['by_day']);
    }

    public function testOmitsIsvFieldsWhenUnset()
    {
        $request = new UpdateScheduleRequest();
        $request->enabled = true;
        $request->threshold = 100;

        $decoded = json_decode((new JsonSerializer())->serialize($request), true);

        $this->assertArrayNotHasKey('balance_minimum', $decoded);
        $this->assertArrayNotHasKey('carry_forward_enabled', $decoded);
        $this->assertArrayNotHasKey('payment_instrument_id', $decoded);
        $this->assertArrayNotHasKey('recurrence', $decoded);
    }
}
