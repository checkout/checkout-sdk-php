<?php

namespace Checkout\Tests\Accounts;

use Checkout\Accounts\AccountsFileRequest;
use Checkout\Accounts\Company;
use Checkout\Accounts\ContactDetails;
use Checkout\Accounts\DateOfBirth;
use Checkout\Accounts\EntityEmailAddresses;
use Checkout\Accounts\EntityRoles;
use Checkout\Accounts\Identification;
use Checkout\Accounts\Individual;
use Checkout\Accounts\InstrumentDetailsFasterPayments;
use Checkout\Accounts\InstrumentDocument;
use Checkout\Accounts\OnboardEntityRequest;
use Checkout\Accounts\PaymentInstrumentRequest;
use Checkout\Accounts\PaymentInstrumentsQuery;
use Checkout\Accounts\Profile;
use Checkout\Accounts\Representative;
use Checkout\Accounts\ReserveRules\Requests\CreateReserveRuleRequest;
use Checkout\Accounts\ReserveRules\Requests\UpdateReserveRuleRequest;
use Checkout\Accounts\ReserveRules\Entities\Rolling;
use Checkout\Accounts\ReserveRules\Entities\HoldingDuration;
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
     * @test
     * @skip API temporarily unavailable
     * @throws CheckoutApiException
     */
    public function shouldGetSubEntityMembers()
    {
        $entityId = $this->createTestEntity();

        $response = $this->checkoutApi->getAccountsClient()->getSubEntityMembers($entityId);

        // Verify the response structure without requiring data to be non-empty
        $this->assertArrayHasKey("data", $response);
        $this->assertTrue(is_array($response["data"]));
    }

    /**
     * @test
     * @skip API temporarily unavailable
     * @throws CheckoutApiException
     */
    public function shouldReinviteSubEntityMember()
    {
        $entityId = $this->createTestEntity();
        
        // First get the actual sub-entity members
        $membersResponse = $this->checkoutApi->getAccountsClient()->getSubEntityMembers($entityId);
        $this->assertArrayHasKey("data", $membersResponse);
        $this->assertTrue(is_array($membersResponse["data"]));
        
        // Skip if no members exist (need actual invited users to reinvite)
        if (empty($membersResponse["data"])) {
            $this->markTestSkipped("No sub-entity members available to reinvite. Members must be invited through Hosted Onboarding first.");
            return;
        }
        
        // Use the first available member's ID
        $firstMember = $membersResponse["data"][0];
        $this->assertArrayHasKey("user_id", $firstMember);
        $userId = $firstMember["user_id"];

        // Now reinvite the actual user
        $response = $this->checkoutApi->getAccountsClient()->reinviteSubEntityMember($entityId, $userId);

        $this->assertArrayHasKey("id", $response);
        $this->assertEquals($userId, $response["id"]);
    }

    /**
     * @test
     * @skip API temporarily unavailable
     * @throws CheckoutApiException
     */
    public function shouldCreateReserveRule()
    {
        $entityId = $this->createTestEntity();
        $request = $this->buildValidReserveRuleRequest();

        $response = $this->checkoutApi->getAccountsClient()->createReserveRule($entityId, $request);

        $this->validateReserveRuleIdResponse($response);
    }

    /**
     * @test
     * @skip API temporarily unavailable
     * @throws CheckoutApiException
     */
    public function shouldGetReserveRules()
    {
        $entityId = $this->createTestEntity();
        
        // First create a reserve rule
        $createRequest = $this->buildValidReserveRuleRequest();
        $this->checkoutApi->getAccountsClient()->createReserveRule($entityId, $createRequest);

        $response = $this->checkoutApi->getAccountsClient()->getReserveRules($entityId);

        $this->validateReserveRulesResponse($response);
    }

    /**
     * @test
     * @skip API temporarily unavailable
     * @throws CheckoutApiException
     */
    public function shouldGetReserveRuleDetails()
    {
        $entityId = $this->createTestEntity();
        
        // First create a reserve rule
        $createRequest = $this->buildValidReserveRuleRequest();
        $createResponse = $this->checkoutApi->getAccountsClient()->createReserveRule($entityId, $createRequest);
        $reserveRuleId = $createResponse["id"];

        $response = $this->checkoutApi->getAccountsClient()->getReserveRuleDetails($entityId, $reserveRuleId);

        $this->validateReserveRuleResponse($response, $createRequest);
    }

    /**
     * @test
     * @skip API temporarily unavailable
     * @throws CheckoutApiException
     */
    public function shouldUpdateReserveRule()
    {
        $entityId = $this->createTestEntity();
        
        // First create a reserve rule
        $createRequest = $this->buildValidReserveRuleRequest();
        $createResponse = $this->checkoutApi->getAccountsClient()->createReserveRule($entityId, $createRequest);
        
        $reserveRuleId = $createResponse["id"];
        
        // Extract ETag from response headers if available
        $etag = null;
        if (isset($createResponse["http_metadata"])) {
            $headers = $createResponse["http_metadata"]->getHeaders();
            if (isset($headers["Etag"]) && is_array($headers["Etag"])) {
                $etag = $headers["Etag"][0];
            }
        }
        
        $updateRequest = $this->buildUpdateReserveRuleRequest();
        $response = $this->checkoutApi->getAccountsClient()->updateReserveRule($entityId, $reserveRuleId, $etag, $updateRequest);

        $this->validateReserveRuleIdResponse($response);
    }

    /**
     * Creates a test entity for sub-entity operations and reserve rules testing
     * Creates a Company entity with Representatives to generate sub-entity members
     * @return string
     * @throws CheckoutApiException
     */
    private function createTestEntity()
    {
        $onboardEntityRequest = new OnboardEntityRequest();
        $onboardEntityRequest->reference = uniqid("test_entity_");
        
        // Add required contact details
        $emailAddresses = new EntityEmailAddresses();
        $emailAddresses->primary = $this->randomEmail();
        $onboardEntityRequest->contact_details = new ContactDetails();
        $onboardEntityRequest->contact_details->phone = $this->getPhone();
        $onboardEntityRequest->contact_details->email_addresses = $emailAddresses;
        
        // Add required profile information
        $onboardEntityRequest->profile = new Profile();
        $onboardEntityRequest->profile->urls = array("https://www.example-test-entity.com");
        $onboardEntityRequest->profile->mccs = array("0742");
        
        // Create a Company entity with Representatives (generates sub-entity members)
        $representative = new Representative();
        $representative->first_name = "John";
        $representative->last_name = "Representative";
        $representative->address = $this->getAddress();
        $representative->identification = new Identification();
        $representative->identification->national_id_number = "AB123456C";
        $representative->date_of_birth = $this->getDateOfBirth();
        $representative->phone = $this->getPhone();
        
        // Set up the company details
        $onboardEntityRequest->company = new Company();
        $onboardEntityRequest->company->business_registration_number = "01234567";
        $onboardEntityRequest->company->legal_name = "Test Sub-Entity Company Inc.";
        $onboardEntityRequest->company->trading_name = "Test Sub-Entity Trading";
        $onboardEntityRequest->company->principal_address = $this->getAddress();
        $onboardEntityRequest->company->registered_address = $this->getAddress();
        $onboardEntityRequest->company->representatives = array($representative);

        $response = $this->checkoutApi->getAccountsClient()->createEntity($onboardEntityRequest);
        return $response["id"];
    }

    /**
     * Builds a valid reserve rule request for testing
     * @return CreateReserveRuleRequest
     */
    private function buildValidReserveRuleRequest()
    {
        $request = new CreateReserveRuleRequest();
        $request->type = "rolling";
        $request->valid_from = date('c', strtotime('+1 day')); // 1 day from now
        
        $rolling = new Rolling();
        $rolling->percentage = 10.0;
        
        $holdingDuration = new HoldingDuration();
        $holdingDuration->weeks = 4;
        $rolling->holding_duration = $holdingDuration;
        
        $request->rolling = $rolling;
        
        return $request;
    }

    /**
     * Builds an update reserve rule request for testing
     * @return UpdateReserveRuleRequest
     */
    private function buildUpdateReserveRuleRequest()
    {
        $request = new UpdateReserveRuleRequest();
        $request->type = "rolling";
        
        $rolling = new Rolling();
        $rolling->percentage = 15.0;
        
        $holdingDuration = new HoldingDuration();
        $holdingDuration->weeks = 6;
        $rolling->holding_duration = $holdingDuration;
        
        $request->rolling = $rolling;
        
        return $request;
    }

    /**
     * Validates reserve rule ID response
     * @param array $response
     */
    private function validateReserveRuleIdResponse($response)
    {
        $this->assertResponse($response, "id");
        $this->assertNotEmpty($response["id"]);
    }

    /**
     * Validates reserve rules list response
     * @param array $response
     */
    private function validateReserveRulesResponse($response)
    {
        $this->assertResponse($response, "data");
        $this->assertNotNull($response["data"]);
        if (!empty($response["data"])) {
            $firstRule = $response["data"][0];
            $this->assertArrayHasKey("id", $firstRule);
            $this->assertArrayHasKey("type", $firstRule);
        }
    }

    /**
     * Validates reserve rule response against original request
     * @param array $response
     * @param CreateReserveRuleRequest $originalRequest
     */
    private function validateReserveRuleResponse($response, $originalRequest)
    {
        $this->assertResponse($response, "id", "type", "valid_from");
        $this->assertEquals($originalRequest->type, $response["type"]);
        $this->assertArrayHasKey("rolling", $response);
        $this->assertEquals($originalRequest->rolling->percentage, $response["rolling"]["percentage"]);
        $this->assertEquals($originalRequest->rolling->holding_duration->weeks, $response["rolling"]["holding_duration"]["weeks"]);
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
