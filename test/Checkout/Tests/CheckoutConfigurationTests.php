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
     * @test
     */
    public function shouldCreateConfiguration()
    {
        $credentials = new StaticKeysSdkCredentials(
            getenv("CHECKOUT_DEFAULT_SECRET_KEY"),
            getenv("CHECKOUT_DEFAULT_PUBLIC_KEY")
        );
        $httpClient = $this->createMock(HttpClientBuilderInterface::class);

        $checkoutLog = new Logger("checkout-sdk-test-php");
        $checkoutLog->pushHandler(new StreamHandler("php://stderr"));
        $checkoutLog->pushHandler(new StreamHandler("checkout-sdk-test-php.log"));

        $configuration = new CheckoutConfiguration(
            $credentials,
            Environment::sandbox(),
            $httpClient,
            $checkoutLog
        );

        $this->assertEquals(Environment::sandbox(), $configuration->getEnvironment());
        $this->assertEquals(
            "https://api.sandbox.checkout.com/",
            $configuration->getEnvironment()->getBaseUri()
        );
    }

    /**
     * @test
     */
    public function shouldCreateConfigurationWithSubdomain()
    {
        $credentials = new StaticKeysSdkCredentials(
            getenv("CHECKOUT_DEFAULT_SECRET_KEY"),
            getenv("CHECKOUT_DEFAULT_PUBLIC_KEY")
        );
        $httpClient = $this->createMock(HttpClientBuilderInterface::class);

        $environmentSubdomain = new EnvironmentSubdomain(Environment::sandbox(), "123dmain");

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
        $this->assertEquals(
            "https://123dmain.api.sandbox.checkout.com/",
            $configuration->getEnvironmentSubdomain()->getBaseUri()
        );
    }

    /**
     * @test
     */
    public function shouldCreateConfigurationWithBadSubdomain()
    {
        $credentials = new StaticKeysSdkCredentials(
            getenv("CHECKOUT_DEFAULT_SECRET_KEY"),
            getenv("CHECKOUT_DEFAULT_PUBLIC_KEY")
        );
        $httpClient = $this->createMock(HttpClientBuilderInterface::class);

        $environmentSubdomain = new EnvironmentSubdomain(Environment::sandbox(), "subdomain");

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
        $this->assertEquals(
            "https://api.sandbox.checkout.com/",
            $configuration->getEnvironmentSubdomain()->getBaseUri()
        );
    }
}
