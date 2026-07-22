<?php

namespace Checkout\Tests\Accounts;

use Checkout\Accounts\AgreedTerms;
use Checkout\JsonSerializer;
use PHPUnit\Framework\TestCase;

class AgreedTermsSerializationTest extends TestCase
{
    public function testAgreedTermsRoundTrip()
    {
        $agreedTerms = new AgreedTerms();
        $agreedTerms->date = "2026-07-20T10:00:00Z";
        $agreedTerms->ip_address = "203.0.113.42";
        $agreedTerms->name = "John Representative";
        $agreedTerms->email = "john@example.com";
        $agreedTerms->version = "1.0";

        $decoded = json_decode((new JsonSerializer())->serialize($agreedTerms), true);

        $this->assertSame("2026-07-20T10:00:00Z", $decoded['date']);
        $this->assertSame("203.0.113.42", $decoded['ip_address']);
        $this->assertSame("John Representative", $decoded['name']);
        $this->assertSame("john@example.com", $decoded['email']);
        $this->assertSame("1.0", $decoded['version']);
    }
}
