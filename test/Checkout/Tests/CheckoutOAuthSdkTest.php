<?php

namespace Checkout\Tests;

use Checkout\CheckoutArgumentException;
use Checkout\CheckoutSdk;
use Checkout\Environment;
use Checkout\HttpClientBuilderInterface;
use Exception;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;

class CheckoutOAuthSdkTest extends UnitTestFixture
{

    /**
     * @test
     */
    public function shouldFailCreatingOAuthSdkWithBothAuthorizationUriAndSubdomain()
    {
        try {
            CheckoutSdk::builder()
                ->oAuth()
                ->clientCredentials("client_id", "client_secret")
                ->environment(Environment::sandbox())
                ->environmentSubdomain("123dmain")
                ->authorizationUri("https://custom.example.com/connect/token")
                ->build();
            $this->fail("shouldn't get here");
        } catch (Exception $e) {
            $this->assertTrue($e instanceof CheckoutArgumentException);
            $this->assertEquals(
                "authorizationUri and environmentSubdomain cannot both be set - the token endpoint is derived " .
                "from your subdomain; combine authorizationUri with useLegacyDomain() if you need a custom " .
                "token host",
                $e->getMessage()
            );
        }
    }

    /**
     * @test
     * @throws CheckoutArgumentException
     */
    public function shouldCreateOAuthSdkWithAuthorizationUriAndLegacyDomain()
    {
        $authorizationUri = "https://custom.example.com/connect/token";

        $client = $this->createMock(ClientInterface::class);
        $client->expects($this->once())
            ->method("request")
            ->with("POST", $authorizationUri, $this->anything())
            ->willReturn(new Response(200, [], json_encode([
                "access_token" => "access_token_value",
                "token_type" => "Bearer",
                "expires_in" => 3600
            ])));

        $httpClientBuilder = $this->createMock(HttpClientBuilderInterface::class);
        $httpClientBuilder->method("getClient")->willReturn($client);

        $checkoutApi = CheckoutSdk::builder()
            ->oAuth()
            ->clientCredentials("client_id", "client_secret")
            ->environment(Environment::sandbox())
            ->useLegacyDomain()
            ->authorizationUri($authorizationUri)
            ->httpClientBuilder($httpClientBuilder)
            ->build();

        $this->assertNotNull($checkoutApi);
    }

}
