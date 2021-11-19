<?php

namespace Checkout\tests\Models\Payments;

use Checkout\Models\Payments\MultibancoSource;
use Checkout\Models\Payments\Payer;
use PHPUnit\Framework\TestCase;

class MultibancoSourceTest extends TestCase
{
    public function testCreate()
    {
        $model = new MultibancoSource('{country}', new Payer('{name}', '{email}', '{document}'));
        $this->assertEquals($model::MODEL_NAME, $model->type);
    }
}
