<?php

namespace Checkout\Tests\Accounts;

use Checkout\Accounts\AccountsFileRequest;
use Checkout\Accounts\BusinessType;
use Checkout\Accounts\Company;
use Checkout\Accounts\ContactDetails;
use Checkout\Accounts\DateOfBirth;
use Checkout\Accounts\DateOfIncorporation;
use Checkout\Accounts\EntityEmailAddresses;
use Checkout\Accounts\EntityRoles;
use Checkout\Accounts\InstrumentDetailsFasterPayments;
use Checkout\Accounts\InstrumentDocument;
use Checkout\Accounts\OnboardEntityRequest;
use Checkout\Accounts\PlaceOfBirth;
use Checkout\Accounts\ProcessingDetails;
use Checkout\Accounts\ProcessingDetailsAch;
use Checkout\Accounts\ProcessingDetailsPayments;
use Checkout\Accounts\PaymentInstrumentRequest;
use Checkout\Accounts\PaymentInstrumentsQuery;
use Checkout\Accounts\Profile;
use Checkout\Accounts\Representative;
use Checkout\Accounts\RepresentativeIndividual;
use Checkout\Accounts\ReserveRules\Requests\CreateReserveRuleRequest;
use Checkout\Accounts\ReserveRules\Requests\UpdateReserveRuleRequest;
use Checkout\Accounts\ReserveRules\Entities\Rolling;
use Checkout\Accounts\ReserveRules\Entities\HoldingDuration;
use Checkout\Accounts\Files\Requests\UploadFileRequest;
use Checkout\CheckoutApi;
use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutException;
use Checkout\CheckoutSdk;
use Checkout\Common\Country;
use Checkout\Common\Currency;
use Checkout\Common\InstrumentType;
use Checkout\Common\Phone;
use Checkout\OAuthScope;
use Checkout\PlatformType;
use Checkout\Tests\SandboxTestFixture;

class AccountsIntegrationTest extends SandboxTestFixture
{
    /**
     * @var CheckoutApi
     */
    private $accountsApi;

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
        $onboardEntityRequest = $this->buildOnboardCompanyRequest();

        $response = $this->accountsApi()->getAccountsClient()->createEntity($onboardEntityRequest);

        $this->assertResponse($response, "id", "reference");

        $response = $this->accountsApi()->getAccountsClient()->getEntity($response["id"]);

        $this->assertResponse(
            $response,
            "id",
            "reference",
            "contact_details",
            "contact_details.phone",
            "contact_details.phone.number",
            "contact_details.email_addresses.primary",
            "company",
            "company.legal_name",
            "company.trading_name",
            "company.business_registration_number"
        );

        $onboardEntityRequest->company->trading_name = "Updated Trading Name";

        $updateResponse = $this->accountsApi()->getAccountsClient()->updateEntity($response["id"], $onboardEntityRequest);

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
        $api = $this->accountsApi();

        $onboardEntityRequest = $this->buildOnboardCompanyRequest();

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

    /**
     * @test
     * @skip API temporarily unavailable
     * @throws CheckoutApiException
     */
    public function shouldGetSubEntityMembers()
    {
        $entityId = $this->createTestEntity();

        $response = $this->accountsApi()->getAccountsClient()->getSubEntityMembers($entityId);

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
        $membersResponse = $this->accountsApi()->getAccountsClient()->getSubEntityMembers($entityId);
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
        $response = $this->accountsApi()->getAccountsClient()->reinviteSubEntityMember($entityId, $userId);

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

        $response = $this->accountsApi()->getAccountsClient()->createReserveRule($entityId, $request);

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
        $this->accountsApi()->getAccountsClient()->createReserveRule($entityId, $createRequest);

        $response = $this->accountsApi()->getAccountsClient()->getReserveRules($entityId);

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
        $createResponse = $this->accountsApi()->getAccountsClient()->createReserveRule($entityId, $createRequest);
        $reserveRuleId = $createResponse["id"];

        $response = $this->accountsApi()->getAccountsClient()->getReserveRuleDetails($entityId, $reserveRuleId);

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
        $createResponse = $this->accountsApi()->getAccountsClient()->createReserveRule($entityId, $createRequest);
        
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
        $response = $this->accountsApi()->getAccountsClient()->updateReserveRule($entityId, $reserveRuleId, $etag, $updateRequest);

        $this->validateReserveRuleIdResponse($response);
    }

    /**
     * @test
     * @skip API temporarily unavailable
     * @throws CheckoutApiException
     */
    public function shouldUploadAndRetrieveFile()
    {
        $entityId = $this->createTestEntity();

        // Test upload file
        $uploadRequest = $this->buildFileUploadRequest();
        $uploadResponse = $this->accountsApi()->getAccountsClient()->uploadFile($entityId, $uploadRequest);

        $this->validateFileUploadResponse($uploadResponse);

        // Test retrieve file id
        $fileId = $uploadResponse["id"];
        $retrieveResponse = $this->accountsApi()->getAccountsClient()->retrieveFile($entityId, $fileId);
        $this->validateFileRetrieveResponse($retrieveResponse);
    }

    /**
     * Creates a test entity for sub-entity operations and reserve rules testing
     * Creates a Company entity with Representatives to generate sub-entity members
     * @return string
     * @throws CheckoutApiException
     */
    private function createTestEntity()
    {
        $onboardEntityRequest = $this->buildOnboardCompanyRequest();
        $response = $this->accountsApi()->getAccountsClient()->createEntity($onboardEntityRequest);
        return $response["id"];
    }

    /**
     * Builds a company OnboardEntityRequest that conforms to the Accounts API v3.0 schema.
     * v3.0 dropped the top-level "individual" field and added the required
     * "processing_details" object (and "date_of_incorporation"/"business_type" on the company);
     * "documents" is required for the full onboarding variant.
     *
     * @return OnboardEntityRequest
     * @throws CheckoutApiException
     */
    private function buildOnboardCompanyRequest()
    {
        $onboardEntityRequest = new OnboardEntityRequest();
        $onboardEntityRequest->reference = uniqid("test_entity_");

        $phone = new Phone();
        $phone->country_code = "GB";
        $phone->number = "2072343000";

        $emailAddresses = new EntityEmailAddresses();
        $emailAddresses->primary = $this->randomEmail();
        $onboardEntityRequest->contact_details = new ContactDetails();
        $onboardEntityRequest->contact_details->phone = $phone;
        $onboardEntityRequest->contact_details->email_addresses = $emailAddresses;

        // The holding currency scope is configured on the platform (USD here), while the
        // processing_details currency reflects the sub-entity region (GBP); they are independent.
        $onboardEntityRequest->profile = new Profile();
        $onboardEntityRequest->profile->urls = array("https://www.example-test-entity.com");
        $onboardEntityRequest->profile->mccs = array("0742");
        $onboardEntityRequest->profile->default_holding_currency = Currency::$USD;
        $onboardEntityRequest->profile->holding_currencies = array(Currency::$USD);

        $dateOfIncorporation = new DateOfIncorporation();
        $dateOfIncorporation->day = 1;
        $dateOfIncorporation->month = 6;
        $dateOfIncorporation->year = 2010;

        $onboardEntityRequest->company = new Company();
        $onboardEntityRequest->company->business_registration_number = "01234567";
        $onboardEntityRequest->company->business_type = BusinessType::$limited_company;
        $onboardEntityRequest->company->legal_name = "Test Sub-Entity Company Inc.";
        $onboardEntityRequest->company->trading_name = "Test Sub-Entity Trading";
        $onboardEntityRequest->company->date_of_incorporation = $dateOfIncorporation;
        $onboardEntityRequest->company->principal_address = $this->getAddress();
        $onboardEntityRequest->company->registered_address = $this->getAddress();
        $onboardEntityRequest->company->representatives = array($this->buildRepresentative());

        $onboardEntityRequest->processing_details = $this->buildProcessingDetails();

        return $onboardEntityRequest;
    }

    /**
     * Builds a v3.0 "person of interest" representative (nested individual details + roles).
     *
     * @return Representative
     */
    private function buildRepresentative()
    {
        $placeOfBirth = new PlaceOfBirth();
        $placeOfBirth->country = Country::$GB;

        $individual = new RepresentativeIndividual();
        $individual->first_name = "John";
        $individual->last_name = "Representative";
        $individual->date_of_birth = $this->getDateOfBirth();
        $individual->place_of_birth = $placeOfBirth;
        $individual->address = $this->getAddress();

        $representative = new Representative();
        $representative->individual = $individual;
        $representative->roles = array(
            EntityRoles::$ubo,
            EntityRoles::$authorised_signatory,
            EntityRoles::$director,
            EntityRoles::$control_person
        );

        return $representative;
    }

    /**
     * Builds the processing_details object required by the Accounts API v3.0 schema.
     *
     * @return ProcessingDetails
     */
    private function buildProcessingDetails()
    {
        $ach = new ProcessingDetailsAch();
        $ach->annual_ach_volume = 1000000;
        $ach->average_ach_transaction_size = 5000;
        $ach->estimated_monthly_credit_volume = 100000;
        $ach->average_credit_amount = 5000;

        $payments = new ProcessingDetailsPayments();
        $payments->ach = $ach;

        $processingDetails = new ProcessingDetails();
        $processingDetails->annual_processing_volume = 1000000;
        $processingDetails->average_transaction_value = 5000;
        $processingDetails->average_order_fulfillment_time = 3;
        $processingDetails->currency = Currency::$GBP;
        $processingDetails->target_countries = array(Country::$GB);
        $processingDetails->payments = $payments;

        return $processingDetails;
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
     * Build a file upload request for testing
     * @return UploadFileRequest
     */
    private function buildFileUploadRequest()
    {
        $request = new UploadFileRequest();
        $request->purpose = "identity_verification";
        
        return $request;
    }

    /**
     * Validates file upload response
     * @param array $response
     */
    private function validateFileUploadResponse($response)
    {
        $this->assertArrayHasKey("id", $response);
        $this->assertArrayHasKey("maximum_size_in_bytes", $response);
        $this->assertArrayHasKey("document_types_for_purpose", $response);
        $this->assertArrayHasKey("_links", $response);
        
        $this->assertNotEmpty($response["id"]);
        $this->assertTrue(is_int($response["maximum_size_in_bytes"]));
        $this->assertTrue(is_array($response["document_types_for_purpose"]));
        $this->assertArrayHasKey("upload", $response["_links"]);
        $this->assertArrayHasKey("self", $response["_links"]);
    }

    /**
     * Validates file retrieve response
     * @param array $response
     */
    private function validateFileRetrieveResponse($response)
    {
        $this->assertArrayHasKey("id", $response);
        $this->assertArrayHasKey("status", $response);
        $this->assertArrayHasKey("size", $response);
        $this->assertArrayHasKey("uploaded_on", $response);
        $this->assertArrayHasKey("purpose", $response);
        $this->assertArrayHasKey("status_reasons", $response);
        $this->assertArrayHasKey("_links", $response);
        
        $this->assertNotEmpty($response["id"]);
        $this->assertNotEmpty($response["status"]);
        $this->assertTrue(is_int($response["size"]));
        $this->assertNotEmpty($response["uploaded_on"]);
        $this->assertNotEmpty($response["purpose"]);
        $this->assertTrue(is_array($response["status_reasons"]));
        $this->assertArrayHasKey("upload", $response["_links"]);
        $this->assertArrayHasKey("self", $response["_links"]);
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
     * The Accounts API v3.0 onboarding is only available on the accounts-scoped OAuth client,
     * so every sub-entity operation in this suite goes through it (memoized).
     *
     * @return CheckoutApi
     * @throws CheckoutArgumentException
     * @throws CheckoutException
     */
    private function accountsApi()
    {
        if ($this->accountsApi === null) {
            $this->accountsApi = $this->getAccountsCheckoutApi();
        }
        return $this->accountsApi;
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
            ->scopes([OAuthScope::$Accounts, OAuthScope::$Files])
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

        $response = $this->accountsApi()->getAccountsClient()->submitFile($fileRequest);

        $this->assertResponse($response, "id");

        return $response;
    }
}
