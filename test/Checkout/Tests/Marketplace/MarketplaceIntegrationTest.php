<?php

namespace Checkout\Tests\Marketplace;

use Checkout\CheckoutApiException;
use Checkout\Marketplace\ContactDetails;
use Checkout\Marketplace\DateOfBirth;
use Checkout\Marketplace\Identification;
use Checkout\Marketplace\Individual;
use Checkout\Marketplace\MarketplaceFileRequest;
use Checkout\Marketplace\OnboardEntityRequest;
use Checkout\Marketplace\Profile;
use Checkout\Marketplace\Transfer\CreateTransferRequest;
use Checkout\Marketplace\Transfer\TransferDestination;
use Checkout\Marketplace\Transfer\TransferSource;
use Checkout\Marketplace\Transfer\TransferType;
use Checkout\PlatformType;
use Checkout\Tests\SandboxTestFixture;

class MarketplaceIntegrationTest extends SandboxTestFixture
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
     */
    public function shouldCreateGetAndUpdateOnboardEntity(): void
    {

        $onboardEntityRequest = new OnboardEntityRequest();
        $onboardEntityRequest->reference = uniqid();
        $onboardEntityRequest->contact_details = new ContactDetails();
        $onboardEntityRequest->contact_details->phone = $this->getPhone();
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

        $response = $this->fourApi->getMarketplaceClient()->createEntity($onboardEntityRequest);

        $this->assertResponse($response, "id", "reference");

        $response = $this->fourApi->getMarketplaceClient()->getEntity($response["id"]);

        $this->assertResponse($response,
            "id",
            "reference",
            "contact_details",
            "contact_details.phone",
            "contact_details.phone.number",
            //"scheme",
            "individual",
            "individual.first_name",
            "individual.last_name",
            "individual.trading_name",
            "individual.national_tax_id"
        );

        $onboardEntityRequest->individual->first_name = "John";

        $updateResponse = $this->fourApi->getMarketplaceClient()->updateEntity($response["id"], $onboardEntityRequest);

        $this->assertResponse($updateResponse, "id");

        self::assertEquals($response["id"], $updateResponse["id"]);
    }

    /**
     * @test
     */
    public function shouldUploadMarketplaceFile(): void
    {
        $fileRequest = new MarketplaceFileRequest();
        $fileRequest->file = self::getCheckoutFilePath();
        $fileRequest->content_type = "image/jpeg";
        $fileRequest->purpose = "identification";

        $response = $this->fourApi->getMarketplaceClient()->submitFile($fileRequest);

        $this->assertResponse($response, "id");
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldInitiateTransferOfFunds(): void
    {
        $transferSource = new TransferSource();
        $transferSource->id = "ent_kidtcgc3ge5unf4a5i6enhnr5m";
        $transferSource->amount = 100;

        $transferDestination = new TransferDestination();
        $transferDestination->id = "ent_w4jelhppmfiufdnatam37wrfc4";

        $transferRequest = new CreateTransferRequest();
        $transferRequest->transfer_type = TransferType::$commission;
        $transferRequest->source = $transferSource;
        $transferRequest->destination = $transferDestination;

        $response = $this->fourApi->getMarketplaceClient()->initiateTransferOfFunds($transferRequest);

        $this->assertResponse($response, "id", "status");
    }

    private function getDateOfBirth(): DateOfBirth
    {
        $dateOfBirth = new DateOfBirth();
        $dateOfBirth->day = 5;
        $dateOfBirth->month = 6;
        $dateOfBirth->year = 1996;

        return $dateOfBirth;
    }

    private function getTestIdentification(): Identification
    {
        $identification = new Identification();
        $identification->national_id_number = "AB123456C";

        return $identification;
    }
}
