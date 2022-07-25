<?php

namespace Checkout\Tests\Customers;

use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\Common\Phone;
use Checkout\Customers\CustomerRequest;
use Checkout\PlatformType;
use Checkout\Tests\SandboxTestFixture;

class CustomersIntegrationTest extends SandboxTestFixture
{

    /**
     * @before
     * @throws CheckoutAuthorizationException
     * @throws CheckoutArgumentException
     * @throws CheckoutException
     */
    public function before()
    {
        $this->init(PlatformType::$default_oauth);
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

        $customerResponse = $this->checkoutApi->getCustomersClient()->create($customerRequest);
        $this->assertResponse($customerResponse, "id");

        $customerDetails = $this->checkoutApi->getCustomersClient()->get($customerResponse["id"]);
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

        $customerResponse = $this->checkoutApi->getCustomersClient()->create($customerRequest);
        $this->assertResponse($customerResponse, "id");

        //Edit Customer
        $customerRequest->email = $this->randomEmail();
        $customerRequest->name = "Changed Name";

        $id = $customerResponse["id"];

        $response = $this->checkoutApi->getCustomersClient()->update($id, $customerRequest);
        self::assertArrayHasKey("http_metadata", $response);
        self::assertEquals(204, $response["http_metadata"]->getStatusCode());

        $customerDetails = $this->checkoutApi->getCustomersClient()->get($id);
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

        $customerResponse = $this->checkoutApi->getCustomersClient()->create($customerRequest);
        $this->assertResponse($customerResponse, "id");

        $id = $customerResponse["id"];
        $deleteResponse = $this->checkoutApi->getCustomersClient()->delete($id);
        self::assertArrayHasKey("http_metadata", $deleteResponse);
        self::assertEquals(204, $deleteResponse["http_metadata"]->getStatusCode());

        $this->expectException(CheckoutApiException::class);
        $this->expectExceptionMessage(self::MESSAGE_404);
        $this->checkoutApi->getCustomersClient()->get($id);
    }

    public function getPhone()
    {
        $phone = new Phone();
        $phone->country_code = "1";
        $phone->number = "4155552671";
        return $phone;
    }
}
