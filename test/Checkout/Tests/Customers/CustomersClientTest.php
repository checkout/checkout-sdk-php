<?php

namespace Checkout\Tests\Customers;

use Checkout\CheckoutApiException;
use Checkout\Customers\CustomerRequest;
use Checkout\Customers\CustomersClient;
use Checkout\PlatformType;
use Checkout\Tests\UnitTestFixture;

class CustomersClientTest extends UnitTestFixture
{
    private $client;

    /**
     * @before
     */
    public function init()
    {
        $this->initMocks(PlatformType::$default);
        $this->client = new CustomersClient($this->apiClient, $this->configuration);
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
     * @doesNotPerformAssertions
     * @throws CheckoutApiException
     */
    public function shouldDeleteCustomer()
    {
        $this->apiClient->method("delete");

        $this->client->delete("customer_id");
    }

}
