<?php

namespace Checkout\Tests\Models\Payments;

use Checkout\Models\Payments\GiropaySource;
use PHPUnit\Framework\TestCase;

class GiropaySourceTest extends TestCase
{
    public function testCreate()
    {
        $model = new GiropaySource('{purpose}', '{bic}');
        $this->assertEquals($model::MODEL_NAME, $model->type);
    }
}
