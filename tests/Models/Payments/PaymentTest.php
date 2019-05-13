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
