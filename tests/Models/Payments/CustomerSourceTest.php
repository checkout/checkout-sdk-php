<?php

namespace Checkout\Tests\Models\Payments;

use Checkout\Models\Payments\CustomerSource;
use Checkout\Tests\Helpers\Sources;
use PHPUnit\Framework\TestCase;

class CustomerSourceTest extends TestCase
{
    public function testCreateID()
    {
        $id = Sources::generateCustomerID();
        $model = new CustomerSource($id);

        $this->assertTrue($model->getId() !== null);
    }

    public function testCreateEmail()
    {
        $email = Sources::generateCustomerEmail();
        $model = new CustomerSource($email);

        $this->assertTrue($model->email !== null);
    }
}
