<?php

namespace Checkout\Tests\Accounts;

use Checkout\Accounts\BusinessType;
use Checkout\Accounts\Company;
use Checkout\Accounts\CompanyPosition;
use Checkout\Accounts\DateOfBirth;
use Checkout\Accounts\DateOfIncorporation;
use Checkout\Accounts\EntityFinancialDetails;
use Checkout\Accounts\EntityRoles;
use Checkout\Accounts\PlaceOfBirth;
use Checkout\Accounts\Representative;
use Checkout\Accounts\RepresentativeIndividual;
use Checkout\Common\Address;
use Checkout\Common\Country;
use Checkout\Common\Currency;
use Checkout\JsonSerializer;
use PHPUnit\Framework\TestCase;

class CompanySerializationTest extends TestCase
{
    public function testCompanyWithV3RepresentativeRoundTrip()
    {
        $dateOfIncorporation = new DateOfIncorporation();
        $dateOfIncorporation->day = 1;
        $dateOfIncorporation->month = 6;
        $dateOfIncorporation->year = 2010;

        $company = new Company();
        $company->business_registration_number = "01234567";
        $company->business_type = BusinessType::$limited_company;
        $company->legal_name = "Super Hero Masks Inc.";
        $company->trading_name = "Super Hero Masks";
        $company->additional_trading_names = array("SHM");
        // The spec enum is [false] only: the flag exists to declare an unregistered company.
        $company->is_registered_company = false;
        $company->regulatory_licence_number = "REG-123456";
        $company->date_of_incorporation = $dateOfIncorporation;
        $company->principal_address = $this->buildAddress("1 Principal Street", "W1T 4TJ");
        $company->registered_address = $this->buildAddress("2 Registered Street", "EC1A 1BB");
        $company->representatives = array($this->buildRepresentative());

        $decoded = json_decode((new JsonSerializer())->serialize($company), true);

        $this->assertSame("01234567", $decoded['business_registration_number']);
        $this->assertSame("limited_company", $decoded['business_type']);
        $this->assertSame("Super Hero Masks Inc.", $decoded['legal_name']);
        $this->assertSame("Super Hero Masks", $decoded['trading_name']);
        $this->assertSame(array("SHM"), $decoded['additional_trading_names']);
        // false must be sent, not dropped like null.
        $this->assertArrayHasKey('is_registered_company', $decoded);
        $this->assertFalse($decoded['is_registered_company']);
        $this->assertSame("REG-123456", $decoded['regulatory_licence_number']);
        $this->assertSame(array("day" => 1, "month" => 6, "year" => 2010), $decoded['date_of_incorporation']);
        $this->assertSame(
            array(
                "address_line1" => "1 Principal Street",
                "city" => "London",
                "zip" => "W1T 4TJ",
                "country" => "GB",
            ),
            $decoded['principal_address']
        );
        $this->assertSame(
            array(
                "address_line1" => "2 Registered Street",
                "city" => "London",
                "zip" => "EC1A 1BB",
                "country" => "GB",
            ),
            $decoded['registered_address']
        );

        $representative = $decoded['representatives'][0];
        $this->assertSame("John", $representative['individual']['first_name']);
        $this->assertSame("GB", $representative['individual']['place_of_birth']['country']);
        $this->assertSame("ceo", $representative['company_position']);
        $this->assertSame(100, $representative['ownership_percentage']);
        $this->assertSame(
            array("ubo", "authorised_signatory", "director", "control_person"),
            $representative['roles']
        );
    }

    /**
     * company.financial_details exists in the EEA and US Company (2.0) variants, with these four
     * fields.
     */
    public function testCompanyV2FinancialDetailsRoundTrip()
    {
        $financialDetails = new EntityFinancialDetails();
        $financialDetails->annual_processing_volume = 120000;
        $financialDetails->average_transaction_value = 500;
        $financialDetails->highest_transaction_value = 2500;
        $financialDetails->currency = Currency::$USD;

        $company = new Company();
        $company->legal_name = "Super Hero Masks Inc.";
        $company->financial_details = $financialDetails;

        $decoded = json_decode((new JsonSerializer())->serialize($company), true);

        $this->assertSame(
            array(
                "annual_processing_volume" => 120000,
                "average_transaction_value" => 500,
                "highest_transaction_value" => 2500,
                "currency" => "USD",
            ),
            $decoded['financial_details']
        );
    }

    public function testRolesSerializeToExactSwaggerValues()
    {
        $this->assertSame("ubo", EntityRoles::$ubo);
        $this->assertSame("authorised_signatory", EntityRoles::$authorised_signatory);
        $this->assertSame("director", EntityRoles::$director);
        $this->assertSame("control_person", EntityRoles::$control_person);
        $this->assertSame("legal_representative", EntityRoles::$legal_representative);
    }

    public function testBusinessTypeValuesMatchSwagger()
    {
        $this->assertSame("individual_or_sole_proprietorship", BusinessType::$individual_or_sole_proprietorship);
        $this->assertSame("general_partnership", BusinessType::$general_partnership);
        $this->assertSame("limited_partnership", BusinessType::$limited_partnership);
        $this->assertSame("scottish_limited_partnership", BusinessType::$scottish_limited_partnership);
        $this->assertSame("public_limited_company", BusinessType::$public_limited_company);
        $this->assertSame("limited_company", BusinessType::$limited_company);
        $this->assertSame("limited_liability_corporation", BusinessType::$limited_liability_corporation);
        $this->assertSame("private_corporation", BusinessType::$private_corporation);
        $this->assertSame("publicly_traded_corporation", BusinessType::$publicly_traded_corporation);
        $this->assertSame("professional_association", BusinessType::$professional_association);
        $this->assertSame("unincorporated_association", BusinessType::$unincorporated_association);
        $this->assertSame("auto_entrepreneur", BusinessType::$auto_entrepreneur);
        $this->assertSame("government_agency", BusinessType::$government_agency);
        $this->assertSame("non_profit_entity", BusinessType::$non_profit_entity);
        $this->assertSame("trust", BusinessType::$trust);
        $this->assertSame("club_or_society", BusinessType::$club_or_society);
        $this->assertSame("regulated_financial_institution", BusinessType::$regulated_financial_institution);
        $this->assertSame("cftc_registered_entity", BusinessType::$cftc_registered_entity);
        $this->assertSame("sec_registered_entity", BusinessType::$sec_registered_entity);
    }

    public function testCompanyPositionValuesMatchSwagger()
    {
        $this->assertSame("ceo", CompanyPosition::$ceo);
        $this->assertSame("cfo", CompanyPosition::$cfo);
        $this->assertSame("coo", CompanyPosition::$coo);
        $this->assertSame("managing_member", CompanyPosition::$managing_member);
        $this->assertSame("general_partner", CompanyPosition::$general_partner);
        $this->assertSame("president", CompanyPosition::$president);
        $this->assertSame("vice_president", CompanyPosition::$vice_president);
        $this->assertSame("treasurer", CompanyPosition::$treasurer);
        $this->assertSame("other_senior_management", CompanyPosition::$other_senior_management);
        $this->assertSame("other_executive_officer", CompanyPosition::$other_executive_officer);
        $this->assertSame("other_non_executive_non_senior", CompanyPosition::$other_non_executive_non_senior);
    }

    private function buildAddress($line1, $zip)
    {
        $address = new Address();
        $address->address_line1 = $line1;
        $address->city = "London";
        $address->zip = $zip;
        $address->country = Country::$GB;
        return $address;
    }

    private function buildRepresentative()
    {
        $dateOfBirth = new DateOfBirth();
        $dateOfBirth->day = 5;
        $dateOfBirth->month = 6;
        $dateOfBirth->year = 1996;

        $placeOfBirth = new PlaceOfBirth();
        $placeOfBirth->country = Country::$GB;

        $address = new Address();
        $address->address_line1 = "CheckoutSdk.com";
        $address->city = "London";
        $address->zip = "W1T 4TJ";
        $address->country = Country::$GB;

        $individual = new RepresentativeIndividual();
        $individual->first_name = "John";
        $individual->last_name = "Representative";
        $individual->date_of_birth = $dateOfBirth;
        $individual->place_of_birth = $placeOfBirth;
        $individual->address = $address;

        $representative = new Representative();
        $representative->individual = $individual;
        $representative->company_position = CompanyPosition::$ceo;
        $representative->ownership_percentage = 100;
        $representative->roles = array(
            EntityRoles::$ubo,
            EntityRoles::$authorised_signatory,
            EntityRoles::$director,
            EntityRoles::$control_person
        );

        return $representative;
    }
}
