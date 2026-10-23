<?php

namespace Checkout\Tests\Issuing\Cards;

use Checkout\ApiClient;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutConfiguration;
use Checkout\CheckoutException;
use Checkout\Environment;
use Checkout\HttpClientBuilderInterface;
use Checkout\Issuing\IssuingClient;
use Checkout\PlatformType;
use Checkout\SdkAuthorization;
use Checkout\SdkCredentialsInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Promise\Create;
use GuzzleHttp\Psr7\Response;
use Monolog\Handler\NullHandler;
use Monolog\Logger;

/**
 * Builds an IssuingClient on a real ApiClient whose HTTP layer is a stubbed Guzzle handler, so
 * tests assert what actually reaches the wire and how responses travel back.
 */
trait StubbedHttpIssuingClientTrait
{
    /**
     * @param int $status The HTTP status every request is answered with.
     * @param string $body The JSON body every request is answered with.
     * @param array $captured Receives every request sent, in order.
     * @return IssuingClient
     * @throws CheckoutArgumentException
     * @throws CheckoutException
     */
    private function buildStubbedClient(int $status, string $body, array &$captured = []): IssuingClient
    {
        $stack = HandlerStack::create();
        $stack->setHandler(function ($request) use ($status, $body, &$captured) {
            $captured[] = $request;
            $response = new Response($status, ["Content-Type" => "application/json"], $body);
            if ($status >= 400) {
                return Create::rejectionFor(new RequestException("error", $request, $response));
            }
            return Create::promiseFor($response);
        });

        $httpBuilder = $this->createMock(HttpClientBuilderInterface::class);
        $httpBuilder->method("getClient")->willReturn(new Client(["handler" => $stack]));

        $sdkCredentials = $this->createMock(SdkCredentialsInterface::class);
        $sdkCredentials
            ->method("getAuthorization")
            ->willReturn(new SdkAuthorization(PlatformType::$default, "sk_sbox_key"));

        $logger = new Logger("checkout-sdk-test-php");
        $logger->pushHandler(new NullHandler());

        $configuration = new CheckoutConfiguration($sdkCredentials, Environment::sandbox(), $httpBuilder, $logger);

        return new IssuingClient(new ApiClient($configuration), $configuration);
    }
}
