<?php

namespace Checkout\Tests;

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
    public function testShouldCreateConfigurationWithSubdomain($subdomain, $expectedUrl)
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
        $this->assertEquals($expectedUrl, $configuration->getEnvironmentSubdomain()->getBaseUri());
    }

    /**
     * @dataProvider invalidSubdomainProvider
     */
    public function testShouldCreateConfigurationWithBadSubdomain($subdomain, $expectedUrl)
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
        $this->assertEquals($expectedUrl, $configuration->getEnvironmentSubdomain()->getBaseUri());
    }

    public function validSubdomainProvider()
    {
        return [
            ["123dmain", "https://123dmain.api.sandbox.checkout.com/"],
            ["123domain", "https://123domain.api.sandbox.checkout.com/"],
            ["1234domain", "https://1234domain.api.sandbox.checkout.com/"],
            ["12345domain", "https://12345domain.api.sandbox.checkout.com/"],
        ];
    }

    public function invalidSubdomainProvider()
    {
        return [
            ["", "https://api.sandbox.checkout.com/"],
            ["123", "https://api.sandbox.checkout.com/"],
            ["123bad", "https://api.sandbox.checkout.com/"],
            ["12345domainBad", "https://api.sandbox.checkout.com/"],
        ];
    }
}
