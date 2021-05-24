<?php

namespace Checkout\tests\Models\Payments;

use Checkout\Models\Payments\PaypalSource;
use PHPUnit\Framework\TestCase;

class PaypalSourceTest extends TestCase
{
    public function testCreate()
    {
        $model = new PaypalSource('{PayPal_invoice_number}');
        $this->assertEquals($model::MODEL_NAME, $model->type);
    }
}
