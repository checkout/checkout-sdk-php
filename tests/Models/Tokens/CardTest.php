<?php

namespace Checkout\Tests\Models\Payments;

use Checkout\Models\Tokens\Card;
use Checkout\Tests\Helpers\Tokens;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class CardTest extends TestCase
{
    public function testCreate()
    {
        $class = new ReflectionClass('Checkout\Models\Tokens\Card');
        $method = $class->getMethod('create');
        $method->setAccessible(true);

        $model = Tokens::generateCardModel();
        $response = $model->getValues();

        $result = $method->invokeArgs($model, array($response));
        $this->assertInstanceOf(Card::class, $result);
    }
}
