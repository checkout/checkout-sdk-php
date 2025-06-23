<?php

namespace Checkout\Tests;

use Checkout\CheckoutApi;
use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\CheckoutSdk;
use Checkout\Common\AccountHolder;
use Checkout\Common\Address;
use Checkout\Common\Country;
use Checkout\Common\Phone;
use Checkout\DefaultHttpClientBuilder;
use Checkout\Environment;
use Checkout\OAuthScope;
use Checkout\Payments\Payer;
use Checkout\PlatformType;
use Exception;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\TestCase;

abstract class SandboxTestFixture extends TestCase
{
    /**
     * @var \Checkout\Previous\CheckoutApi
     */
    protected $previousApi;

    /**
     * @var CheckoutApi
     */
    protected $checkoutApi;

    const MESSAGE_404 = "The API response status code (404) does not indicate success.";
    const MESSAGE_403 = "The API response status code (403) does not indicate success.";
    const MESSAGE_409 = "The API response status code (409) does not indicate success.";

    private $logger;

    /**
     * @param mixed $platformType
     * @throws CheckoutAuthorizationException
     * @throws CheckoutArgumentException
     * @throws CheckoutException
     */
    protected function init($platformType): void
    {
        $configClient = ["timeout" => 60];

        $this->logger = new Logger("checkout-sdk-test-php");
        $this->logger->pushHandler(new StreamHandler("php://stderr"));
        $this->logger->pushHandler(new StreamHandler("checkout-sdk-test-php.log"));

        switch ($platformType) {
            case PlatformType::$previous:
                $this->previousApi = CheckoutSdk::builder()
                    ->previous()
                    ->staticKeys()
                    ->environment(Environment::sandbox())
                    ->publicKey(getenv("CHECKOUT_PREVIOUS_PUBLIC_KEY"))
                    ->secretKey(getenv("CHECKOUT_PREVIOUS_SECRET_KEY"))
                    ->httpClientBuilder(new DefaultHttpClientBuilder($configClient))
                    ->logger($this->logger)
                    ->build();
                return;
            case PlatformType::$default:
                $this->checkoutApi = CheckoutSdk::builder()
                    ->staticKeys()
                    ->publicKey(getenv("CHECKOUT_DEFAULT_PUBLIC_KEY"))
                    ->secretKey(getenv("CHECKOUT_DEFAULT_SECRET_KEY"))
                    ->environment(Environment::sandbox())
                    ->httpClientBuilder(new DefaultHttpClientBuilder($configClient))
                    ->logger($this->logger)
                    ->build();
                return;
            case PlatformType::$default_oauth:
                $this->checkoutApi = CheckoutSdk::builder()
                    ->oAuth()
                    ->clientCredentials(
                        getenv("CHECKOUT_DEFAULT_OAUTH_CLIENT_ID"),
                        getenv("CHECKOUT_DEFAULT_OAUTH_CLIENT_SECRET")
                    )
                    ->scopes([
                        OAuthScope::$Files,
                        OAuthScope::$Flow,
                        OAuthScope::$Fx,
                        OAuthScope::$Gateway,
                        OAuthScope::$Accounts,
                        OAuthScope::$SessionsApp,
                        OAuthScope::$SessionsBrowser,
                        OAuthScope::$Vault,
                        OAuthScope::$PayoutsBankDetails,
                        OAuthScope::$TransfersCreate,
                        OAuthScope::$TransfersView,
                        OAuthScope::$BalancesView,
                        OAuthScope::$VaultCardMetadata,
                        OAuthScope::$FinancialActions
                    ])
                    ->environment(Environment::sandbox())
                    ->httpClientBuilder(new DefaultHttpClientBuilder($configClient))
                    ->logger($this->logger)
                    ->build();
                return;
            default:
                $this->logger->error("Invalid platform type");
                throw new CheckoutAuthorizationException("Invalid platform type");
        }
    }

    protected function assertResponse($obj, ...$properties): void
    {
        $this->assertNotNull($obj);
        $this->assertNotEmpty($properties);
        foreach ($properties as $property) {
            if (strpos($property, ".") !== false) {
                $props = explode(".", $property);
                $testingObj = $obj[$props[0]];
                $joined = implode(".", array_slice($props, 1));
                $this->assertResponse($testingObj, $joined);
            } else {
                $this->assertNotNull($obj[$property]);
                $this->assertNotEmpty($obj[$property]);
            }
        }
    }

    protected function randomEmail(): string
    {
        return uniqid() . "@checkout-sdk-net.com";
    }

    protected function idempotencyKey(): string
    {
        $s = md5(uniqid(rand(), true));
        return substr($s, 0, 8) .
            '-' . substr($s, 8, 4) .
            '-' . substr($s, 12, 4) .
            '-' . substr($s, 16, 4) .
            '-' . substr($s, 20);
    }

    public static function getCheckoutFilePath(): string
    {
        return __DIR__ . DIRECTORY_SEPARATOR . "Resources" . DIRECTORY_SEPARATOR . "checkout.jpeg";
    }

    protected function getAddress(): Address
    {
        $address = new Address();
        $address->address_line1 = "CheckoutSdk.com";
        $address->address_line2 = "max_10_c__";
        $address->city = "London";
        $address->state = "London";
        $address->zip = "W1T 4TJ";
        $address->country = Country::$GB;
        return $address;
    }

    protected function getPhone(): Phone
    {
        $phone = new Phone();
        $phone->country_code = "1";
        $phone->number = "4155552671";
        return $phone;
    }

    protected function getAccountHolder(): AccountHolder
    {
        $accountHolder = new AccountHolder();
        $accountHolder->first_name = "John";
        $accountHolder->last_name = "Doe";
        $accountHolder->phone = $this->getPhone();
        $accountHolder->billing_address = $this->getAddress();
        return $accountHolder;
    }

    protected function retriable(
        callable $func,
        ?callable $predicate = null,
        int $timeout = 2
    ) {
        $currentAttempt = 1;
        $maxAttempts = 10;
        while ($currentAttempt <= $maxAttempts) {
            try {
                $response = $func();
                if (is_null($predicate)) {
                    return $response;
                }
                if ($predicate($response)) {
                    return $response;
                }
            } catch (Exception $ex) {
                $this->logger->warning(
                    "Request/Predicate failed with error '$ex' - retry $currentAttempt/$maxAttempts"
                );
            }
            $currentAttempt++;
            sleep($timeout);
        }
        throw new AssertionFailedError("Max attempts reached!");
    }

    protected function checkErrorItem(callable $func, string $errorItem): void
    {
        try {
            $func();
        } catch (Exception $ex) {
            self::assertTrue($ex instanceof CheckoutApiException);
            self::assertContains(
                $errorItem,
                $ex->error_details["error_codes"],
                "Was actually: " . implode(',', $ex->error_details["error_codes"])
            );
        }
    }

    protected function getPayer(): Payer
    {
        $payer = new Payer();
        $payer->email = "bruce@wayne-enterprises.com";
        $payer->name = "Bruce Wayne";
        $payer->document = "53033315550";
        return $payer;
    }
}
