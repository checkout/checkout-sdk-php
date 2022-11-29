<?php

namespace Checkout\Tests\Accounts;

use Checkout\Accounts\AccountsFileRequest;
use Checkout\Accounts\Company;
use Checkout\Accounts\ContactDetails;
use Checkout\Accounts\DateOfBirth;
use Checkout\Accounts\EntityEmailAddresses;
use Checkout\Accounts\Identification;
use Checkout\Accounts\Individual;
use Checkout\Accounts\InstrumentDetailsFasterPayments;
use Checkout\Accounts\InstrumentDocument;
use Checkout\Accounts\OnboardEntityRequest;
use Checkout\Accounts\PaymentInstrumentRequest;
use Checkout\Accounts\PaymentInstrumentsQuery;
use Checkout\Accounts\Profile;
use Checkout\Accounts\Representative;
use Checkout\CheckoutApi;
use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutException;
use Checkout\CheckoutSdk;
use Checkout\Common\Country;
use Checkout\Common\Currency;
use Checkout\Common\InstrumentType;
use Checkout\OAuthScope;
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
     * @throws CheckoutArgumentException
     * @throws CheckoutException
     */
    public function shouldCreateAndRetrievePaymentInstrument()
    {
        $api = $this->getAccountsCheckoutApi();

        $representative = new Representative();
        $representative->first_name = "John";
        $representative->last_name = "Montana";
        $representative->address = $this->getAddress();
        $representative->identification = new Identification();
        $representative->identification->national_id_number = "AB123456C";

        $onboardEntityRequest = new OnboardEntityRequest();
        $onboardEntityRequest->reference = uniqid();
        $onboardEntityRequest->contact_details = new ContactDetails();
        $onboardEntityRequest->contact_details->phone = $this->getPhone();
        $onboardEntityRequest->contact_details->email_addresses = new EntityEmailAddresses();
        $onboardEntityRequest->contact_details->email_addresses->primary = $this->randomEmail();
        $onboardEntityRequest->profile = new Profile();
        $onboardEntityRequest->profile->urls = array("https://www.superheroexample.com");
        $onboardEntityRequest->profile->mccs = array("0742");
        $onboardEntityRequest->company = new Company();
        $onboardEntityRequest->company->business_registration_number = "01234567";
        $onboardEntityRequest->company->legal_name = "Super Hero Masks Inc.";
        $onboardEntityRequest->company->trading_name = "Super Hero Masks";
        $onboardEntityRequest->company->principal_address = $this->getAddress();
        $onboardEntityRequest->company->registered_address = $this->getAddress();
        $onboardEntityRequest->company->representatives = array($representative);

        $entity = $api->getAccountsClient()->createEntity($onboardEntityRequest);

        $file = $this->uploadFile();

        $instrumentRequest = new PaymentInstrumentRequest();
        $instrumentRequest->label = "Barclays";
        $instrumentRequest->type = InstrumentType::$bank_account;
        $instrumentRequest->currency = Currency::$GBP;
        $instrumentRequest->country = Country::$GB;
        $instrumentRequest->default = false;
        $instrumentRequest->document = new InstrumentDocument();
        $instrumentRequest->document->type = "bank_statement";
        $instrumentRequest->document->file_id = $file["id"];
        $instrumentRequest->instrument_details = new InstrumentDetailsFasterPayments();
        $instrumentRequest->instrument_details->account_number = "12334454";
        $instrumentRequest->instrument_details->bank_code = "050389";

        $instrumentResponse = $api->getAccountsClient()->createBankPaymentInstrument($entity["id"], $instrumentRequest);
        $this->assertResponse($instrumentResponse, "id");

        $instrumentDetails = $api->getAccountsClient()->retrievePaymentInstrumentDetails(
            $entity["id"],
            $instrumentResponse["id"]
        );
        $this->assertResponse(
            $instrumentDetails,
            "id",
            "status",
            "label",
            "type",
            "currency",
            "country",
            "document"
        );

        $queryResponse = $api->getAccountsClient()->queryPaymentInstruments($entity["id"], new PaymentInstrumentsQuery());
        $this->assertResponse($queryResponse, "data");
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldUploadAccountsFile()
    {
        $this->uploadFile();
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

    /**
     * @return CheckoutApi
     * @throws CheckoutArgumentException
     * @throws CheckoutException
     */
    private function getAccountsCheckoutApi()
    {
        return CheckoutSdk::builder()->oAuth()
            ->clientCredentials(
                getenv("CHECKOUT_DEFAULT_OAUTH_ACCOUNTS_CLIENT_ID"),
                getenv("CHECKOUT_DEFAULT_OAUTH_ACCOUNTS_CLIENT_SECRET")
            )
            ->scopes([OAuthScope::$Accounts])
            ->build();
    }

    /**
     * @return array
     * @throws CheckoutApiException
     */
    public function uploadFile()
    {
        $fileRequest = new AccountsFileRequest();
        $fileRequest->file = $this->getCheckoutFilePath();
        $fileRequest->content_type = "image/jpeg";
        $fileRequest->purpose = "bank_verification";

        $response = $this->checkoutApi->getAccountsClient()->submitFile($fileRequest);

        $this->assertResponse($response, "id");

        return $response;
    }
}
