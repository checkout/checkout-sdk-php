<?php

namespace Checkout\Tests\Customers;

use Checkout\CheckoutApiException;
use Checkout\Common\Phone;
use Checkout\Customers\CustomerRequest;
use Checkout\PlatformType;
use Checkout\Tests\SandboxTestFixture;

class CustomersIntegrationTest extends SandboxTestFixture
{

    /**
     * @before
     */
    public function before()
    {
        $this->init(PlatformType::$default);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreateAndGetCustomer()
    {
        $customerRequest = new CustomerRequest();
        $customerRequest->email = $this->randomEmail();
        $customerRequest->name = "Customer";
        $customerRequest->phone = $this->getPhone();

        $customerResponse = $this->defaultApi->getCustomersClient()->create($customerRequest);
        $this->assertResponse($customerResponse, "id");

        $customerDetails = $this->defaultApi->getCustomersClient()->get($customerResponse["id"]);
        $this->assertResponse(
            $customerDetails,
            "email",
            "name",
            "phone"
        );
        $this->assertEquals($customerRequest->name, $customerDetails["name"]);
        $this->assertEquals($customerRequest->email, $customerDetails["email"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreateAndUpdateCustomer()
    {
        //Create Customer
        $customerRequest = new CustomerRequest();
        $customerRequest->email = $this->randomEmail();
        $customerRequest->name = "Customer";
        $customerRequest->phone = $this->getPhone();

        $customerResponse = $this->defaultApi->getCustomersClient()->create($customerRequest);
        $this->assertResponse($customerResponse, "id");

        //Edit Customer
        $customerRequest->email = $this->randomEmail();
        $customerRequest->name = "Changed Name";

        $id = $customerResponse["id"];

        $updateResponse = $this->defaultApi->getCustomersClient()->update($id, $customerRequest);
        self::assertArrayHasKey("http_metadata", $updateResponse);
        self::assertEquals(204, $updateResponse["http_metadata"]->getStatusCode());

        $customerDetails = $this->defaultApi->getCustomersClient()->get($id);
        $this->assertResponse(
            $customerDetails,
            "email",
            "name",
            "phone"
        );
        $this->assertEquals($customerRequest->name, $customerDetails["name"]);
        $this->assertEquals($customerRequest->email, $customerDetails["email"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreateAndDeleteCustomer()
    {
        $customerRequest = new CustomerRequest();
        $customerRequest->email = $this->randomEmail();
        $customerRequest->name = "Customer";

        $customerResponse = $this->defaultApi->getCustomersClient()->create($customerRequest);
        $this->assertResponse($customerResponse, "id");

        $id = $customerResponse["id"];
        $deleteResponse = $this->defaultApi->getCustomersClient()->delete($id);
        self::assertArrayHasKey("http_metadata", $deleteResponse);
        self::assertEquals(204, $deleteResponse["http_metadata"]->getStatusCode());

        $this->expectException(CheckoutApiException::class);
        $this->expectExceptionMessage(self::MESSAGE_404);
        $this->defaultApi->getCustomersClient()->get($id);
    }

    public function getPhone()
    {
        $phone = new Phone();
        $phone->country_code = "1";
        $phone->number = "4155552671";
        return $phone;
    }
}
