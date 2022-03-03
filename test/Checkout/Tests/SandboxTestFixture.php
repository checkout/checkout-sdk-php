<?php

namespace Checkout\Tests;

use Checkout\CheckoutApi;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutDefaultSdk;
use Checkout\CheckoutFourSdk;
use Checkout\Environment;
use Checkout\FourOAuthScope;
use Checkout\PlatformType;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use PHPUnit\Runner\Exception;
use Psr\Log\LoggerInterface;
use function PHPUnit\Framework\assertNotEmpty;
use function PHPUnit\Framework\assertNotNull;

abstract class SandboxTestFixture extends TestCase
{

    protected CheckoutApi $defaultApi;
    protected \Checkout\Four\CheckoutApi $fourApi;

    protected const MESSAGE_404 = "The API response status code (404) does not indicate success.";
    protected const MESSAGE_403 = "The API response status code (403) does not indicate success.";

    private LoggerInterface $logger;

    protected function init(string $platformType): void
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
                    FourOAuthScope::$Vault, FourOAuthScope::$PayoutsBankDetails]);
                $builder->setEnvironment(Environment::sandbox());
                $builder->setFilesEnvironment(Environment::sandbox());
                $builder->setLogger($this->logger);
                $this->fourApi = $builder->build();
                return;
            default:
                $this->logger->error("Invalid platform type");
                throw new CheckoutAuthorizationException("Invalid platform type");
        }

    }

    protected function assertResponse($obj, ...$properties): void // @phpstan-ignore-line
    {
        assertNotNull($obj);
        assertNotEmpty($properties);
        foreach ($properties as $property) {
            if (str_contains($property, ".")) {
                // "a.b.c" to "a","b","c"
                $props = explode(".", $property);
                // value("a")
                $testingObj = $obj[$props[0]];
                // collect to "b.c"
                $joined = implode(".", array_slice($props, 1));
                self::assertResponse($testingObj, $joined);
            } else {
                //echo "\e[0;30;45massertResponse[property] testing=" . json_encode($property) . " found=" . json_encode($obj[$property]) . "\n";
                assertNotNull($obj[$property]);
                assertNotEmpty($obj[$property]);
            }
        }
    }

    protected function randomEmail(): string
    {
        return uniqid() . "@checkout-sdk-net.com";
    }

    protected function idempotencyKey(): string
    {
        return substr(uniqid(), 0, 8);
    }

    public static function getCheckoutFilePath(): string
    {
        return __DIR__ . DIRECTORY_SEPARATOR . "Resources" . DIRECTORY_SEPARATOR . "checkout.jpeg";
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
            } catch (\Exception $ex) {
                $this->logger->warning("Request/Predicate failed with error '${ex}' - retry ${currentAttempt}/${maxAttempts}");
            }
            $currentAttempt++;
            sleep(2);
        }
        throw new Exception("Max attempts reached!");
    }

}
