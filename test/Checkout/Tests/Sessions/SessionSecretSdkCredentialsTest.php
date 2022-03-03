<?php

namespace Checkout\Tests\Sessions;

use Checkout\CheckoutAuthorizationException;
use Checkout\Sessions\SessionSecretSdkCredentials;
use Checkout\Tests\UnitTestFixture;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertNotNull;

class SessionSecretSdkCredentialsTest extends UnitTestFixture
{

    /**
     * @test
     */
    public function shouldCreateSessionSecretSdkCredentials(): void
    {
        $credentials = new SessionSecretSdkCredentials("test");
        assertEquals($credentials->secret, "test");
    }

    /**
     * @test
     * @throws CheckoutAuthorizationException
     */
    public function shouldGetAuthorization(): void
    {
        $credentials = new SessionSecretSdkCredentials("test");
        $auth = $credentials->getAuthorization("custom");
        assertNotNull($auth);
        assertEquals($auth->getAuthorizationHeader(), "test");
    }
}
