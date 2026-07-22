<?php

namespace Checkout\Tests\Accounts;

use Checkout\Accounts\BusinessType;
use Checkout\Accounts\Company;
use Checkout\Accounts\CompanyPosition;
use Checkout\Accounts\DateOfBirth;
use Checkout\Accounts\DateOfIncorporation;
use Checkout\Accounts\EntityRoles;
use Checkout\Accounts\PlaceOfBirth;
use Checkout\Accounts\Representative;
use Checkout\Accounts\RepresentativeIndividual;
use Checkout\Common\Address;
use Checkout\Common\Country;
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
        $company->is_registered_company = true;
        $company->regulatory_licence_number = "REG-123456";
        $company->date_of_incorporation = $dateOfIncorporation;
        $company->representatives = array($this->buildRepresentative());

        $decoded = json_decode((new JsonSerializer())->serialize($company), true);

        $this->assertSame("01234567", $decoded['business_registration_number']);
        $this->assertSame("limited_company", $decoded['business_type']);
        $this->assertSame("Super Hero Masks Inc.", $decoded['legal_name']);
        $this->assertSame("Super Hero Masks", $decoded['trading_name']);
        $this->assertSame(array("SHM"), $decoded['additional_trading_names']);
        $this->assertTrue($decoded['is_registered_company']);
        $this->assertSame("REG-123456", $decoded['regulatory_licence_number']);
        $this->assertSame(2010, $decoded['date_of_incorporation']['year']);

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
        $this->assertSame("limited_liability_corporation", BusinessType::$limited_liability_corporation);
        $this->assertSame("government_agency", BusinessType::$government_agency);
        $this->assertSame("non_profit_entity", BusinessType::$non_profit_entity);
        $this->assertSame("trust", BusinessType::$trust);
        $this->assertSame("scottish_limited_partnership", BusinessType::$scottish_limited_partnership);
    }

    public function testCompanyPositionValuesMatchSwagger()
    {
        $this->assertSame("ceo", CompanyPosition::$ceo);
        $this->assertSame("managing_member", CompanyPosition::$managing_member);
        $this->assertSame("general_partner", CompanyPosition::$general_partner);
        $this->assertSame("other_non_executive_non_senior", CompanyPosition::$other_non_executive_non_senior);
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
