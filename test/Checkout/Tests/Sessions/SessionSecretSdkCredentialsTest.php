<?php

namespace Checkout\Tests\Sessions;

use Checkout\CheckoutAuthorizationException;
use Checkout\Sessions\SessionSecretSdkCredentials;
use Checkout\Tests\UnitTestFixture;

class SessionSecretSdkCredentialsTest extends UnitTestFixture
{

    /**
     * @test
     */
    public function shouldCreateSessionSecretSdkCredentials()
    {
        $credentials = new SessionSecretSdkCredentials("test");
        $this->assertEquals("test", $credentials->secret);
    }

    /**
     * @test
     * @throws CheckoutAuthorizationException
     */
    public function shouldGetAuthorization()
    {
        $credentials = new SessionSecretSdkCredentials("test");
        $auth = $credentials->getAuthorization("custom");
        $this->assertNotNull($auth);
        $this->assertEquals("test", $auth->getAuthorizationHeader());
    }
}
