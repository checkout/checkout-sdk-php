<?php

namespace Checkout\Tests;

use Checkout\ApiClient;
use Checkout\CheckoutConfiguration;
use Checkout\Environment;
use Checkout\HttpClientBuilderInterface;
use Checkout\SdkAuthorization;
use Checkout\SdkCredentialsInterface;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

abstract class UnitTestFixture extends MockeryTestCase
{

    protected string $platformType;

    protected CheckoutConfiguration $configuration;

    // Default
    protected static string $validDefaultSk = "sk_test_fde517a8-3f01-41ef-b4bd-4282384b0a64";
    protected static string $validDefaultPk = "pk_test_fe70ff27-7c32-4ce1-ae90-5691a188ee7b";
    protected static string $invalidDefaultSk = "sk_test_asdsad3q4dq";
    protected static string $invalidDefaultPk = "pk_test_q414dasds";

    // Four
    protected static string $validFourSk = "sk_sbox_m73dzbpy7cf3gfd46xr4yj5xo4e";
    protected static string $validFourPk = "pk_sbox_pkhpdtvmkgf7hdnpwnbhw7r2uic";
    protected static string $invalidFourSk = "sk_sbox_m73dzbpy7c-f3gfd46xr4yj5xo4e";
    protected static string $invalidFourPk = "pk_sbox_pkh";

    /**
     * @var mixed
     */
    protected $apiClient;

    public function initMocks(string $platformType): void
    {
        $this->platformType = $platformType;
        $sdkAuthorization = new SdkAuthorization($platformType, "key");

        $sdkCredentials = $this->createMock(SdkCredentialsInterface::class);
        $sdkCredentials->method("getAuthorization")->willReturn($sdkAuthorization);

        $httpBuilder = $this->createMock(HttpClientBuilderInterface::class);

        $checkoutLog = new Logger("checkout-sdk-test-php");
        $checkoutLog->pushHandler(new StreamHandler("php://stderr"));
        $checkoutLog->pushHandler(new StreamHandler("checkout-sdk-test-php.log"));

        $this->configuration = new CheckoutConfiguration($sdkCredentials, Environment::sandbox(), $httpBuilder, $checkoutLog);

        $this->apiClient = $this->createStub(ApiClient::class);

        $checkoutLog->info("Unit tests are starting");
    }
}
