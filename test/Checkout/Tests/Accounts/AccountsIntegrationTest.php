<?php

namespace Checkout\Tests\Accounts;

use Checkout\Accounts\AccountsFileRequest;
use Checkout\Accounts\ContactDetails;
use Checkout\Accounts\DateOfBirth;
use Checkout\Accounts\EntityEmailAddresses;
use Checkout\Accounts\Identification;
use Checkout\Accounts\Individual;
use Checkout\Accounts\OnboardEntityRequest;
use Checkout\Accounts\Profile;
use Checkout\CheckoutApiException;
use Checkout\PlatformType;
use Checkout\Tests\SandboxTestFixture;

class AccountsIntegrationTest extends SandboxTestFixture
{
    /**
     * @before
     * @throws
     */
    public function before()
    {
        $this->init(PlatformType::$default_oauth);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreateGetAndUpdateOnboardEntity()
    {
        $onboardEntityRequest = new OnboardEntityRequest();
        $onboardEntityRequest->reference = uniqid();
        $emailAddresses = new EntityEmailAddresses();
        $emailAddresses->primary = $this->randomEmail();
        $onboardEntityRequest->contact_details = new ContactDetails();
        $onboardEntityRequest->contact_details->phone = $this->getPhone();
        $onboardEntityRequest->contact_details->email_addresses = $emailAddresses;
        $onboardEntityRequest->profile = new Profile();
        $onboardEntityRequest->profile->urls = array("https://www.superheroexample.com");
        $onboardEntityRequest->profile->mccs = array("0742");
        $onboardEntityRequest->individual = new Individual();
        $onboardEntityRequest->individual->first_name = "Bruce";
        $onboardEntityRequest->individual->last_name = "Wayne";
        $onboardEntityRequest->individual->trading_name = "Batman's Super Hero Masks";
        $onboardEntityRequest->individual->registered_address = $this->getAddress();
        $onboardEntityRequest->individual->national_tax_id = "TAX123456";
        $onboardEntityRequest->individual->date_of_birth = $this->getDateOfBirth();
        $onboardEntityRequest->individual->identification = $this->getTestIdentification();

        $response = $this->checkoutApi->getAccountsClient()->createEntity($onboardEntityRequest);

        $this->assertResponse($response, "id", "reference");

        $response = $this->checkoutApi->getAccountsClient()->getEntity($response["id"]);

        $this->assertResponse(
            $response,
            "id",
            "reference",
            "contact_details",
            "contact_details.phone",
            "contact_details.phone.number",
            "contact_details.email_addresses.primary",
            "individual",
            "individual.first_name",
            "individual.last_name",
            "individual.trading_name",
            "individual.national_tax_id"
        );

        $onboardEntityRequest->individual->first_name = "John";

        $updateResponse = $this->checkoutApi->getAccountsClient()->updateEntity($response["id"], $onboardEntityRequest);

        $this->assertResponse($updateResponse, "id");

        $this->assertEquals($response["id"], $updateResponse["id"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldUploadAccountsFile()
    {
        $fileRequest = new AccountsFileRequest();
        $fileRequest->file = $this->getCheckoutFilePath();
        $fileRequest->content_type = "image/jpeg";
        $fileRequest->purpose = "identification";

        $response = $this->checkoutApi->getAccountsClient()->submitFile($fileRequest);

        $this->assertResponse($response, "id");
    }

    private function getDateOfBirth()
    {
        $dateOfBirth = new DateOfBirth();
        $dateOfBirth->day = 5;
        $dateOfBirth->month = 6;
        $dateOfBirth->year = 1996;

        return $dateOfBirth;
    }

    private function getTestIdentification()
    {
        $identification = new Identification();
        $identification->national_id_number = "AB123456C";

        return $identification;
    }
}
