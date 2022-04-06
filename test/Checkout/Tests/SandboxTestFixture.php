<?php

namespace Checkout\Tests;

use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutDefaultSdk;
use Checkout\CheckoutFourSdk;
use Checkout\Common\Address;
use Checkout\Common\Country;
use Checkout\Common\Phone;
use Checkout\Environment;
use Checkout\Four\FourOAuthScope;
use Checkout\PlatformType;
use Exception;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\TestCase;

abstract class SandboxTestFixture extends TestCase
{

    protected $defaultApi;
    protected $fourApi;

    const MESSAGE_404 = "The API response status code (404) does not indicate success.";
    const MESSAGE_403 = "The API response status code (403) does not indicate success.";

    private $logger;

    protected function init($platformType)
    {
        $this->logger = new Logger("checkout-sdk-test-php");
        $this->logger->pushHandler(new StreamHandler("php://stderr"));
        $this->logger->pushHandler(new StreamHandler("checkout-sdk-test-php.log"));
        switch ($platformType) {
            case PlatformType::$default:
                $builder = CheckoutDefaultSdk::staticKeys();
                $builder->setPublicKey(getenv("CHECKOUT_PUBLIC_KEY"));
                $builder->setSecretKey(getenv("CHECKOUT_SECRET_KEY"));
                $builder->setEnvironment(Environment::sandbox());
                $builder->setLogger($this->logger);
                $this->defaultApi = $builder->build();
                return;
            case PlatformType::$four:
                $builder = CheckoutFourSdk::staticKeys();
                $builder->setPublicKey(getenv("CHECKOUT_FOUR_PUBLIC_KEY"));
                $builder->setSecretKey(getenv("CHECKOUT_FOUR_SECRET_KEY"));
                $builder->setEnvironment(Environment::sandbox());
                $builder->setLogger($this->logger);
                $this->fourApi = $builder->build();
                return;
            case PlatformType::$fourOAuth:
                $builder = CheckoutFourSdk::oAuth();
                $builder->clientCredentials(getenv("CHECKOUT_FOUR_OAUTH_CLIENT_ID"), getenv("CHECKOUT_FOUR_OAUTH_CLIENT_SECRET"));
                $builder->scopes([FourOAuthScope::$Files, FourOAuthScope::$Flow, FourOAuthScope::$Fx, FourOAuthScope::$Gateway,
                    FourOAuthScope::$Marketplace, FourOAuthScope::$SessionsApp, FourOAuthScope::$SessionsBrowser,
                    FourOAuthScope::$Vault, FourOAuthScope::$PayoutsBankDetails, FourOAuthScope::$TransfersCreate,
                    FourOAuthScope::$BalancesView]);
                $builder->setEnvironment(Environment::sandbox());
                $builder->setLogger($this->logger);
                $this->fourApi = $builder->build();
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

    protected function randomEmail()
    {
        return uniqid() . "@checkout-sdk-net.com";
    }

    protected function idempotencyKey()
    {
        return substr(uniqid(), 0, 8);
    }

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
     * @return mixed
     */
    protected function retriable(callable $func, callable $predicate = null)
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
            sleep(2);
        }
        throw new AssertionFailedError("Max attempts reached!");
    }
}
