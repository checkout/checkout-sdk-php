<?php

namespace Checkout\Tests\Customers\Four;

use Checkout\CheckoutApiException;
use Checkout\Common\Phone;
use Checkout\Customers\Four\CustomerRequest;
use Checkout\PlatformType;
use Checkout\Tests\SandboxTestFixture;

class CustomersIntegrationTest extends SandboxTestFixture
{

    /**
     * @before
     */
    public function before(): void
    {
        $this->init(PlatformType::$fourOAuth);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreateAndGetCustomer(): void
    {
        $customerRequest = new CustomerRequest();
        $customerRequest->email = $this->randomEmail();
        $customerRequest->name = "Customer";
        $customerRequest->phone = $this->getPhone();

        $customerResponse = $this->fourApi->getCustomersClient()->create($customerRequest);
        $this->assertResponse($customerResponse, "id");

        $customerDetails = $this->fourApi->getCustomersClient()->get($customerResponse["id"]);
        $this->assertResponse($customerDetails,
            "email",
            "name",
            "phone");
        self::assertEquals($customerRequest->name, $customerDetails["name"]);
        self::assertEquals($customerRequest->email, $customerDetails["email"]);

    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreateAndUpdateCustomer(): void
    {
        //Create Customer
        $customerRequest = new CustomerRequest();
        $customerRequest->email = $this->randomEmail();
        $customerRequest->name = "Customer";
        $customerRequest->phone = $this->getPhone();

        $customerResponse = $this->fourApi->getCustomersClient()->create($customerRequest);
        $this->assertResponse($customerResponse, "id");

        //Edit Customer
        $customerRequest->email = $this->randomEmail();
        $customerRequest->name = "Changed Name";

        $id = $customerResponse["id"];

        $this->fourApi->getCustomersClient()->update($id, $customerRequest);

        $customerDetails = $this->fourApi->getCustomersClient()->get($id);
        $this->assertResponse($customerDetails,
            "email",
            "name",
            "phone");
        self::assertEquals($customerRequest->name, $customerDetails["name"]);
        self::assertEquals($customerRequest->email, $customerDetails["email"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreateAndDeleteCustomer(): void
    {
        $customerRequest = new CustomerRequest();
        $customerRequest->email = $this->randomEmail();
        $customerRequest->name = "Customer";

        $customerResponse = $this->fourApi->getCustomersClient()->create($customerRequest);
        $this->assertResponse($customerResponse, "id");

        $id = $customerResponse["id"];
        $this->fourApi->getCustomersClient()->delete($id);

        $this->expectException(CheckoutApiException::class);
        $this->expectExceptionMessage(self::MESSAGE_404);
        $this->fourApi->getCustomersClient()->get($id);
    }

    public function getPhone(): Phone
    {
        $phone = new Phone();
        $phone->country_code = "1";
        $phone->number = "4155552671";
        return $phone;
    }
}
