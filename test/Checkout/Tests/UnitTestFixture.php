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

    protected $platformType;

    protected $configuration;

    // Previous
    protected static $validPreviousSk = "sk_test_fde517a8-3f01-41ef-b4bd-4282384b0a64";
    protected static $validPreviousPk = "pk_test_fe70ff27-7c32-4ce1-ae90-5691a188ee7b";
    protected static $invalidPreviousSk = "sk_test_asdsad3q4dq";
    protected static $invalidPreviousPk = "pk_test_q414dasds";

    // Default
    protected static $validDefaultSk = "sk_sbox_m73dzbpy7cf3gfd46xr4yj5xo4e";
    protected static $validDefaultPk = "pk_sbox_pkhpdtvmkgf7hdnpwnbhw7r2uic";
    protected static $invalidDefaultSk = "sk_sbox_m73dzbpy7c-f3gfd46xr4yj5xo4e";
    protected static $invalidDefaultPk = "pk_sbox_pkh";

    /**
     * @var mixed
     */
    protected $apiClient;

    public function initMocks($platformType)
    {
        $this->platformType = $platformType;
        $sdkAuthorization = new SdkAuthorization($platformType, "key");

        $sdkCredentials = $this->createMock(SdkCredentialsInterface::class);
        $sdkCredentials->method("getAuthorization")->willReturn($sdkAuthorization);

        $httpBuilder = $this->createMock(HttpClientBuilderInterface::class);

        $checkoutLog = new Logger("checkout-sdk-test-php");
        $checkoutLog->pushHandler(new StreamHandler("php://stderr"));
        $checkoutLog->pushHandler(new StreamHandler("checkout-sdk-test-php.log"));

        $this->configuration = new CheckoutConfiguration(
            $sdkCredentials,
            Environment::sandbox(),
            $httpBuilder,
            $checkoutLog
        );

        $this->apiClient = $this->createMock(ApiClient::class);

        $checkoutLog->info("Unit tests are starting");
    }

    /**
     * @return string
     */
    public static function getCheckoutFilePath()
    {
        return __DIR__ . DIRECTORY_SEPARATOR . "Resources" . DIRECTORY_SEPARATOR . "checkout.jpeg";
    }
}
