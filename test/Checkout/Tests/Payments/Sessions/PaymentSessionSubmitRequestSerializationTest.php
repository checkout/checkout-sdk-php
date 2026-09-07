<?php

namespace Checkout\Tests\Payments\Sessions;

use Checkout\Common\AmountAllocations;
use Checkout\Common\Commission;
use Checkout\JsonSerializer;
use Checkout\Payments\Sessions\PaymentSessionSubmitRequest;
use PHPUnit\Framework\TestCase;

/**
 * Schema validation tests for PaymentSessionSubmitRequest.
 *
 * Covers amount_allocations, added to SubmitPaymentSessionsRequest in the 2026-08-21 spec
 * (min 1, max 50 items; each item requires id and amount, with optional reference and
 * commission). Values are the spec's field-level example values from
 * shared/swagger-latest.json.
 */
class PaymentSessionSubmitRequestSerializationTest extends TestCase
{
    public function testSerializesAmountAllocationsWithAllItemFields()
    {
        $commission = new Commission();
        $commission->amount = 10;
        $commission->percentage = 12.5;

        $allocation = new AmountAllocations();
        $allocation->id = "ent_w4jelhppmfiufdnatam37wrfc4";
        $allocation->amount = 1;
        $allocation->reference = "ORD-123A";
        $allocation->commission = $commission;

        $request = new PaymentSessionSubmitRequest();
        $request->session_data = "session_data_token";
        $request->amount_allocations = array($allocation);

        $decoded = json_decode((new JsonSerializer())->serialize($request), true);

        $this->assertSame("session_data_token", $decoded['session_data']);
        $this->assertArrayHasKey('amount_allocations', $decoded);
        $this->assertCount(1, $decoded['amount_allocations']);

        $item = $decoded['amount_allocations'][0];
        $this->assertSame("ent_w4jelhppmfiufdnatam37wrfc4", $item['id']);
        $this->assertSame(1, $item['amount']);
        $this->assertSame("ORD-123A", $item['reference']);
        $this->assertSame(10, $item['commission']['amount']);
        $this->assertSame(12.5, $item['commission']['percentage']);
    }

    public function testSerializesAmountAllocationsWithOnlyRequiredItemFields()
    {
        $allocation = new AmountAllocations();
        $allocation->id = "ent_w4jelhppmfiufdnatam37wrfc4";
        $allocation->amount = 1;

        $request = new PaymentSessionSubmitRequest();
        $request->session_data = "session_data_token";
        $request->amount_allocations = array($allocation);

        $decoded = json_decode((new JsonSerializer())->serialize($request), true);

        $item = $decoded['amount_allocations'][0];
        $this->assertSame("ent_w4jelhppmfiufdnatam37wrfc4", $item['id']);
        $this->assertSame(1, $item['amount']);
        $this->assertArrayNotHasKey('reference', $item);
        $this->assertArrayNotHasKey('commission', $item);
    }

    public function testSerializesMultipleAmountAllocations()
    {
        $first = new AmountAllocations();
        $first->id = "ent_w4jelhppmfiufdnatam37wrfc4";
        $first->amount = 1;

        $second = new AmountAllocations();
        $second->id = "ent_fa6psq242dcbeagbdb35ppy4qm";
        $second->amount = 2;

        $request = new PaymentSessionSubmitRequest();
        $request->session_data = "session_data_token";
        $request->amount_allocations = array($first, $second);

        $decoded = json_decode((new JsonSerializer())->serialize($request), true);

        $this->assertCount(2, $decoded['amount_allocations']);
        $this->assertSame("ent_w4jelhppmfiufdnatam37wrfc4", $decoded['amount_allocations'][0]['id']);
        $this->assertSame("ent_fa6psq242dcbeagbdb35ppy4qm", $decoded['amount_allocations'][1]['id']);
    }

    public function testOmitsAmountAllocationsWhenUnset()
    {
        $request = new PaymentSessionSubmitRequest();
        $request->session_data = "session_data_token";

        $decoded = json_decode((new JsonSerializer())->serialize($request), true);

        $this->assertSame("session_data_token", $decoded['session_data']);
        $this->assertArrayNotHasKey('amount_allocations', $decoded);
    }
}
