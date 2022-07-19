<?php

namespace Checkout\Tests\Customers\Four;

use Checkout\Customers\Four\CustomerRequest;
use Checkout\Customers\Four\CustomersClient;
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
     */
    public function init()
    {
        $this->initMocks(PlatformType::$four);
        $this->client = new CustomersClient($this->apiClient, $this->configuration);
    }


    /**
     * @test
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
     */
    public function shouldDeleteCustomer()
    {
        $this->apiClient->method("delete")
            ->willReturn("foo");

        $response = $this->client->delete("customer_id");
        $this->assertNotNull($response);
    }

}