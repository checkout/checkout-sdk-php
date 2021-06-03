<?php

namespace Checkout\tests\Models\Payments;

use Checkout\Models\Payments\Recipient;
use PHPUnit\Framework\TestCase;

class RecipientTest extends TestCase
{
    public function testCreate()
    {
        $model = new Recipient('{dob}', '{account}', '{zip}', '{first_name}', '{last_name}','{country}');
        $this->assertInstanceOf(Recipient::class, $model);
    }
}
