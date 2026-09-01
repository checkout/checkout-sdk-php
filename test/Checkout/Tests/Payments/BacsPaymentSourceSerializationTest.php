<?php

namespace Checkout\Tests\Payments;

use Checkout\Common\PaymentSourceType;
use Checkout\JsonSerializer;
use Checkout\Payments\Request\Source\Apm\RequestBacsSource;
use PHPUnit\Framework\TestCase;

/**
 * Schema validation tests for RequestBacsSource against the PaymentRequestBacsSource swagger
 * schema, which declares a type and an id only.
 */
class BacsPaymentSourceSerializationTest extends TestCase
{
    /**
     * @test
     */
    public function shouldSerializeTheTypeAndTheInstrumentId()
    {
        $source = new RequestBacsSource();
        $source->id = "src_wmlfc3zyhqzehihu7giusaaawu";

        $decoded = json_decode((new JsonSerializer())->serialize($source), true);

        $this->assertSame("bacs", $decoded["type"]);
        $this->assertSame("src_wmlfc3zyhqzehihu7giusaaawu", $decoded["id"]);
        $this->assertCount(2, $decoded);
    }

    /**
     * @test
     */
    public function shouldCarryTheBacsPaymentSourceType()
    {
        $this->assertSame("bacs", PaymentSourceType::$bacs);
        $this->assertSame(PaymentSourceType::$bacs, (new RequestBacsSource())->type);
    }
}
