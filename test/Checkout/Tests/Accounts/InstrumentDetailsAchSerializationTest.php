<?php

namespace Checkout\Tests\Accounts;

use Checkout\Accounts\InstrumentAccountType;
use Checkout\Accounts\InstrumentDetailsAch;
use Checkout\JsonSerializer;
use PHPUnit\Framework\TestCase;

class InstrumentDetailsAchSerializationTest extends TestCase
{
    public function testInstrumentDetailsAchRoundTrip()
    {
        $details = new InstrumentDetailsAch();
        $details->account_number = "0123456789";
        $details->routing_number = "021000021";
        $details->account_type = InstrumentAccountType::$checking;

        $decoded = json_decode((new JsonSerializer())->serialize($details), true);

        $this->assertSame("0123456789", $decoded['account_number']);
        $this->assertSame("021000021", $decoded['routing_number']);
        $this->assertSame("checking", $decoded['account_type']);
    }

    public function testAccountTypeSerializesToExactSwaggerValues()
    {
        $savings = new InstrumentDetailsAch();
        $savings->account_type = InstrumentAccountType::$savings;
        $decodedSavings = json_decode((new JsonSerializer())->serialize($savings), true);
        $this->assertSame("savings", $decodedSavings['account_type']);

        $checking = new InstrumentDetailsAch();
        $checking->account_type = InstrumentAccountType::$checking;
        $decodedChecking = json_decode((new JsonSerializer())->serialize($checking), true);
        $this->assertSame("checking", $decodedChecking['account_type']);
    }
}
