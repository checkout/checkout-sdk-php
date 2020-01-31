<?php

namespace Checkout\Tests\Models\Payments;

use Checkout\Models\Payments\TokenSource;
use Checkout\Tests\Helpers\Tokens;
use PHPUnit\Framework\TestCase;

class TokenSourceTest extends TestCase
{
    public function testCreate()
    {
        $token = new TokenSource(Tokens::generateId());
        $this->assertInstanceOf(TokenSource::class, $token);
    }
}
