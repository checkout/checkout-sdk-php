<?php

namespace Checkout\Tests\Issuing\Transactions;

use Checkout\CheckoutApi;
use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutException;
use Checkout\CheckoutSdk;
use Checkout\DefaultHttpClientBuilder;
use Checkout\Environment;
use Checkout\Issuing\Transactions\Requests\TransactionsQuery;
use Checkout\OAuthScope;
use Checkout\PlatformType;
use Checkout\Tests\Issuing\AbstractIssuingIntegrationTest;

class TransactionsIntegrationTest extends AbstractIssuingIntegrationTest
{
    /**
     * @before
     * @throws CheckoutArgumentException
     * @throws CheckoutException
     */
    public function before()
    {
        $this->init(PlatformType::$default);
        $this->issuingApi = $this->createTransactionsIssuingApi();
    }

    /**
     * @throws CheckoutArgumentException
     * @throws CheckoutException
     * @return CheckoutApi
     */
    private function createTransactionsIssuingApi()
    {
        $configClient = [
            "timeout" => 60
        ];

        return CheckoutSdk::builder()
            ->oAuth()
            ->clientCredentials(
                getenv("CHECKOUT_DEFAULT_OAUTH_ISSUING_CLIENT_ID"),
                getenv("CHECKOUT_DEFAULT_OAUTH_ISSUING_CLIENT_SECRET")
            )
            ->scopes([
                OAuthScope::$IssuingClient,
                OAuthScope::$IssuingCardMgmt,
                OAuthScope::$IssuingTransactionsRead
            ])
            ->environment(Environment::sandbox())
            ->httpClientBuilder(new DefaultHttpClientBuilder($configClient))
            ->build();
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetListTransactions()
    {
        $query = new TransactionsQuery();
        $response = $this->issuingApi->getIssuingClient()->getListTransactions($query);

        $this->assertNotNull($response);
        $this->assertArrayHasKey("limit", $response);
        $this->assertNotNull($response["limit"]);
        $this->assertArrayHasKey("skip", $response);
        $this->assertNotNull($response["skip"]);
        $this->assertArrayHasKey("total_count", $response);
        $this->assertNotNull($response["total_count"]);
        $this->assertArrayHasKey("data", $response);
        $this->assertNotNull($response["data"]);
        $this->assertTrue(is_array($response["data"]));
        $this->assertGreaterThan(0, count($response["data"]));
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetSingleTransaction()
    {
        $query = new TransactionsQuery();
        $transactions = $this->issuingApi->getIssuingClient()->getListTransactions($query);
        
        $response = $this->issuingApi->getIssuingClient()->getSingleTransaction($transactions["data"][0]["id"]);

        $this->assertNotNull($response);
        $this->assertArrayHasKey("id", $response);
        $this->assertNotEmpty($response["id"]);
        $this->assertArrayHasKey("created_on", $response);
        $this->assertNotNull($response["created_on"]);
        $this->assertArrayHasKey("status", $response);
        $this->assertNotNull($response["status"]);
        $this->assertArrayHasKey("transaction_type", $response);
        $this->assertNotNull($response["transaction_type"]);
        $this->assertArrayHasKey("client", $response);
        $this->assertNotNull($response["client"]);
        $this->assertArrayHasKey("entity", $response);
        $this->assertNotNull($response["entity"]);
        $this->assertArrayHasKey("card", $response);
        $this->assertNotNull($response["card"]);
        $this->assertArrayHasKey("cardholder", $response);
        $this->assertNotNull($response["cardholder"]);
        $this->assertArrayHasKey("amounts", $response);
        $this->assertNotNull($response["amounts"]);
        $this->assertArrayHasKey("merchant", $response);
        $this->assertNotNull($response["merchant"]);
        $this->assertArrayHasKey("messages", $response);
        $this->assertNotNull($response["messages"]);
    }
}
