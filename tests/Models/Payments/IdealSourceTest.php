<?php

namespace Checkout\tests\Models\Payments;

use Checkout\Models\Payments\IdealSource;
use PHPUnit\Framework\TestCase;

class IdealSourceTest extends TestCase
{
    public function testCreate()
    {
        $model = new IdealSource('{iDEAL_issuer}');
        $this->assertEquals($model::MODEL_NAME, $model->type);
    }
}
