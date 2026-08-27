<?php

namespace Checkout\Tests\Payments;

use Checkout\JsonSerializer;
use Checkout\Payments\VoidRequest;
use PHPUnit\Framework\TestCase;

class VoidRequestSerializationTest extends TestCase
{
    public function testVoidRequestSerializesAmountWhenSet()
    {
        $request = new VoidRequest();
        $request->amount = 100;
        $request->reference = "partial-void";

        $decoded = json_decode((new JsonSerializer())->serialize($request), true);

        $this->assertSame(100, $decoded['amount']);
        $this->assertSame("partial-void", $decoded['reference']);
    }

    public function testVoidRequestOmitsAmountWhenUnset()
    {
        $request = new VoidRequest();
        $request->reference = "full-void";

        $decoded = json_decode((new JsonSerializer())->serialize($request), true);

        $this->assertArrayNotHasKey('amount', $decoded);
        $this->assertSame("full-void", $decoded['reference']);
    }
}
