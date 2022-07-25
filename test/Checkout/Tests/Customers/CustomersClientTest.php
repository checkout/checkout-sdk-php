<?php

namespace Checkout\Tests\Customers;

use Checkout\AuthorizationType;
use Checkout\CheckoutApiException;
use Checkout\Customers\CustomerRequest;
use Checkout\Customers\CustomersClient;
use Checkout\PlatformType;
use Checkout\Tests\UnitTestFixture;

class CustomersClientTest extends UnitTestFixture
{
    /**
     * @var CustomersClient
     */
    private $client;

    /**
     * @before
     * @throws CheckoutAuthorizationException
     * @throws CheckoutArgumentException
     * @throws CheckoutException
     */
    public function init()
    {
        $this->initMocks(PlatformType::$previous);
        $this->client = new CustomersClient($this->apiClient, $this->configuration, AuthorizationType::$secretKeyOrOAuth);
    }


    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetCustomer()
    {

        $this->apiClient
            ->method("get")
            ->willReturn("foo");

        $response = $this->client->get("customer_id");
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreateCustomer()
    {

        $this->apiClient
            ->method("post")
            ->willReturn("foo");

        $response = $this->client->create(new CustomerRequest());
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldUpdateCustomer()
    {

        $this->apiClient
            ->method("patch")
            ->willReturn("foo");

        $response = $this->client->update("customer_id", new CustomerRequest());
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldDeleteCustomer()
    {
        $this->apiClient->method("delete")
            ->willReturn("foo");

        $response = $this->client->delete("customer_id");

        $this->assertNotNull($response);
    }

}
