<?php

namespace Checkout\Tests;

use Checkout\CheckoutApi;
use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\CheckoutSdk;
use Checkout\Common\Address;
use Checkout\Common\Country;
use Checkout\Common\Phone;
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
     * @throws CheckoutAuthorizationException
     * @throws CheckoutArgumentException
     * @throws CheckoutException
     */
    protected function init($platformType)
    {
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
                    ->logger($this->logger)
                    ->build();
                return;
            case PlatformType::$default:
                $this->checkoutApi = CheckoutSdk::builder()->staticKeys()
                    ->publicKey(getenv("CHECKOUT_DEFAULT_PUBLIC_KEY"))
                    ->secretKey(getenv("CHECKOUT_DEFAULT_SECRET_KEY"))
                    ->environment(Environment::sandbox())
                    ->logger($this->logger)
                    ->build();
                return;
            case PlatformType::$default_oauth:
                $this->checkoutApi = CheckoutSdk::builder()->oAuth()
                    ->clientCredentials(getenv("CHECKOUT_DEFAULT_OAUTH_CLIENT_ID"), getenv("CHECKOUT_DEFAULT_OAUTH_CLIENT_SECRET"))
                    ->scopes([OAuthScope::$Files, OAuthScope::$Flow, OAuthScope::$Fx, OAuthScope::$Gateway,
                        OAuthScope::$Marketplace, OAuthScope::$SessionsApp, OAuthScope::$SessionsBrowser,
                        OAuthScope::$Vault, OAuthScope::$PayoutsBankDetails, OAuthScope::$TransfersCreate,
                        OAuthScope::$TransfersView, OAuthScope::$BalancesView, OAuthScope::$VaultCardMetadata,
                        OAuthScope::$FinancialActions])
                    ->environment(Environment::sandbox())
                    ->logger($this->logger)
                    ->build();
                return;
            default:
                $this->logger->error("Invalid platform type");
                throw new CheckoutAuthorizationException("Invalid platform type");
        }
    }

    protected function assertResponse($obj, ...$properties)
    {
        $this->assertNotNull($obj);
        $this->assertNotEmpty($properties);
        foreach ($properties as $property) {
            if (strpos($property, ".") !== false) {
                // "a.b.c" to "a","b","c"
                $props = explode(".", $property);
                // value("a")
                $testingObj = $obj[$props[0]];
                // collect to "b.c"
                $joined = implode(".", array_slice($props, 1));
                $this->assertResponse($testingObj, $joined);
            } else {
                //echo "\e[0;30;45massertResponse[property] testing=" . json_encode($property) . " found=" . json_encode($obj[$property]) . "\n";
                $this->assertNotNull($obj[$property]);
                $this->assertNotEmpty($obj[$property]);
            }
        }
    }

    /**
     * @return string
     */
    protected function randomEmail()
    {
        return uniqid() . "@checkout-sdk-net.com";
    }

    /**
     * @return false|string
     */
    protected function idempotencyKey()
    {
        return substr(uniqid(), 0, 8);
    }

    /**
     * @return string
     */
    public static function getCheckoutFilePath()
    {
        return __DIR__ . DIRECTORY_SEPARATOR . "Resources" . DIRECTORY_SEPARATOR . "checkout.jpeg";
    }

    /**
     * @return Address
     */
    protected function getAddress()
    {
        $address = new Address();
        $address->address_line1 = "CheckoutSdk.com";
        $address->address_line2 = "90 Tottenham Court Road";
        $address->city = "London";
        $address->state = "London";
        $address->zip = "W1T 4TJ";
        $address->country = Country::$GB;
        return $address;
    }

    /**
     * @return Phone
     */
    protected function getPhone()
    {
        $phone = new Phone();
        $phone->country_code = "1";
        $phone->number = "4155552671";
        return $phone;
    }

    /**
     * @param callable $func
     * @param callable|null $predicate
     * @param int $timeout
     * @return mixed
     */
    protected function retriable(callable $func, callable $predicate = null, $timeout = 2)
    {
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
                $this->logger->warning("Request/Predicate failed with error '${ex}' - retry ${currentAttempt}/${maxAttempts}");
            }
            $currentAttempt++;
            sleep($timeout);
        }
        throw new AssertionFailedError("Max attempts reached!");
    }

    /**
     * @param callable $func
     * @param string $errorItem
     */
    protected function checkErrorItem(callable $func, $errorItem)
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

    /**
     * @return Payer
     */
    protected function getPayer()
    {
        $payer = new Payer();
        $payer->email = "bruce@wayne-enterprises.com";
        $payer->name = "Bruce Wayne";
        $payer->document = "53033315550";
        return $payer;
    }
}
