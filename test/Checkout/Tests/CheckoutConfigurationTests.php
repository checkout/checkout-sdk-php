<?php

namespace Checkout\Tests;

use Checkout\CheckoutArgumentException;
use Checkout\CheckoutConfiguration;
use Checkout\Environment;
use Checkout\EnvironmentSubdomain;
use Checkout\HttpClientBuilderInterface;
use Checkout\StaticKeysSdkCredentials;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class CheckoutConfigurationTests extends MockeryTestCase
{
    /**
     * @dataProvider validSubdomainProvider
     */
    public function testShouldCreateConfigurationWithSubdomain($subdomain, $expectedApiUrl, $expectedAuthUrl)
    {
        $credentials = new StaticKeysSdkCredentials(
            getenv("CHECKOUT_DEFAULT_SECRET_KEY"),
            getenv("CHECKOUT_DEFAULT_PUBLIC_KEY")
        );
        $httpClient = $this->createMock(HttpClientBuilderInterface::class);

        $environmentSubdomain = new EnvironmentSubdomain(Environment::sandbox(), $subdomain);

        $checkoutLog = new Logger("checkout-sdk-test-php");
        $checkoutLog->pushHandler(new StreamHandler("php://stderr"));
        $checkoutLog->pushHandler(new StreamHandler("checkout-sdk-test-php.log"));

        $configuration = new CheckoutConfiguration(
            $credentials,
            Environment::sandbox(),
            $httpClient,
            $checkoutLog
        );

        $configuration->setEnvironmentSubdomain($environmentSubdomain);

        $this->assertEquals(Environment::sandbox(), $configuration->getEnvironment());
        $this->assertEquals($expectedApiUrl, $configuration->getEnvironmentSubdomain()->getBaseUri());
        $this->assertEquals($expectedAuthUrl, $configuration->getEnvironmentSubdomain()->getAuthorizationUri());
    }

    /**
     * @dataProvider invalidSubdomainProvider
     */
    public function testShouldRejectConfigurationWithBadSubdomain($subdomain)
    {
        $this->expectException(CheckoutArgumentException::class);
        $this->expectExceptionMessage("invalid environment subdomain");

        new EnvironmentSubdomain(Environment::sandbox(), $subdomain);
    }

    public function testShouldRejectSubdomainWithTrailingNewline()
    {
        try {
            new EnvironmentSubdomain(Environment::sandbox(), "vkuhvk4v\n");
            $this->fail("a subdomain with a trailing newline should be rejected");
        } catch (CheckoutArgumentException $e) {
            $this->assertEquals(
                "invalid environment subdomain - provide your merchant-specific subdomain, typically your " .
                "client ID excluding the cli_ prefix (see https://api-reference.checkout.com/#section/Base-URLs)",
                $e->getMessage()
            );
        }
    }

    public function validSubdomainProvider()
    {
        return [
            ["a", "https://a.api.sandbox.checkout.com/", "https://a.access.sandbox.checkout.com/connect/token"],
            ["ab", "https://ab.api.sandbox.checkout.com/", "https://ab.access.sandbox.checkout.com/connect/token"],
            ["abc", "https://abc.api.sandbox.checkout.com/", "https://abc.access.sandbox.checkout.com/connect/token"],
            ["abc1", "https://abc1.api.sandbox.checkout.com/", "https://abc1.access.sandbox.checkout.com/connect/token"],
            ["12345domain", "https://12345domain.api.sandbox.checkout.com/", "https://12345domain.access.sandbox.checkout.com/connect/token"],
            ["pl-vkuhvk4v", "https://pl-vkuhvk4v.api.sandbox.checkout.com/", "https://pl-vkuhvk4v.access.sandbox.checkout.com/connect/token"],
            ["pl-abc123", "https://pl-abc123.api.sandbox.checkout.com/", "https://pl-abc123.access.sandbox.checkout.com/connect/token"],
        ];
    }

    public function invalidSubdomainProvider()
    {
        return [
            [""],
            [" "],
            ["   "],
            [" - "],
            ["a b"],
            ["ab c1."],
            ["foo-"],
            ["-foo"],
            ["ABC"],
            ["test-123"],
            ["foo-bar"],
            ["pl-"],
            ["vkuhvk4v\n"],
        ];
    }

    public function testShouldCreateConfigurationWithSubdomainForProduction()
    {
        $subdomain = "1234prod";
        $credentials = new StaticKeysSdkCredentials(
            getenv("CHECKOUT_DEFAULT_SECRET_KEY"),
            getenv("CHECKOUT_DEFAULT_PUBLIC_KEY")
        );
        $httpClient = $this->createMock(HttpClientBuilderInterface::class);

        $environmentSubdomain = new EnvironmentSubdomain(Environment::production(), $subdomain);

        $checkoutLog = new Logger("checkout-sdk-test-php");
        $checkoutLog->pushHandler(new StreamHandler("php://stderr"));
        $checkoutLog->pushHandler(new StreamHandler("checkout-sdk-test-php.log"));

        $configuration = new CheckoutConfiguration(
            $credentials,
            Environment::production(),
            $httpClient,
            $checkoutLog
        );

        $configuration->setEnvironmentSubdomain($environmentSubdomain);

        $this->assertEquals(Environment::production(), $configuration->getEnvironment());
        $this->assertEquals("https://1234prod.api.checkout.com/", $configuration->getEnvironmentSubdomain()->getBaseUri());
        $this->assertEquals("https://1234prod.access.checkout.com/connect/token", $configuration->getEnvironmentSubdomain()->getAuthorizationUri());
    }

    public function testEnvironmentSandboxHasCorrectForwardAndIdentityUrls()
    {
        $env = Environment::sandbox();
        $this->assertEquals("https://forward.sandbox.checkout.com/", $env->getForwardUri());
        $this->assertEquals("https://identity-verification.sandbox.checkout.com/", $env->getIdentityUri());
    }

    public function testEnvironmentProductionHasCorrectForwardAndIdentityUrls()
    {
        $env = Environment::production();
        $this->assertEquals("https://forward.checkout.com/", $env->getForwardUri());
        $this->assertEquals("https://identity-verification.checkout.com/", $env->getIdentityUri());
    }

}
