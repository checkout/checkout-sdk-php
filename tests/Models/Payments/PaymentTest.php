<?php

namespace Checkout\tests\Models\Payments;

use Checkout\Models\Payments\Payment;
use Checkout\tests\Helpers\HttpHandlers;
use Checkout\tests\Helpers\Payments;
use PHPUnit\Framework\TestCase;

class PaymentTest extends TestCase
{

    /**
     * @param Payment $model
     * @dataProvider providerSuccess
     */
    public function testIsSuccessful(Payment $model, $success)
    {
        $this->assertEquals($success, $model->isSuccessful());
    }

    public function providerSuccess()
    {
        $failed = Payments::generateModel();
        $failed->approved = false;

        return array(array($failed, false),
                     array(Payments::generateModel(), true));
    }

    public function testSetIdempotencyKey()
    {
        $model = Payments::generateModel();
        $uuid = HttpHandlers::generateUUID();
        $model->setIdempotencyKey($uuid);
        $this->assertEquals($uuid, $model->getIdempotencyKey());
    }

    public function testGetRedirection()
    {
        $model = Payments::generateModel();
        $this->assertTrue(is_string($model->getRedirection()));
    }

    public function testIsPending()
    {
        $model = Payments::generateModel();
        $this->assertTrue(is_bool($model->isPending()));
    }

    public function testGetSourceID()
    {
        $model = Payments::generateModel();
        $this->assertTrue(is_string($model->getSourceId()));
        //When model does not contain ID
        unset($model->source->id);
        $this->assertTrue(is_string($model->getSourceId()));
        self::assertEquals("", $model->getSourceId());
        //Generate source as array
        $model->source = array(
            "id" => "src_b081e6b34078d0eb40c9a94283",
            "type" => "card");
        $this->assertTrue(is_string($model->getSourceId()));
        self::assertEquals("src_b081e6b34078d0eb40c9a94283", $model->getSourceId());
        //When array does not have ID
        $model->source = array("type" => "card");
        $this->assertTrue(is_string($model->getSourceId()));
        self::assertEquals("", $model->getSourceId());
    }

    public function testIsValid()
    {
        $model = Payments::generateModel();
        $this->assertTrue(is_bool($model->isValid()));
    }

    public function testIsFlagged()
    {
        $model = Payments::generateModel();
        $this->assertTrue(is_bool($model->isFlagged()));
    }
}
