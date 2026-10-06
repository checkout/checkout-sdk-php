<?php

namespace Checkout\Tests\Accounts;

use Checkout\Accounts\AdditionalDocument;
use Checkout\Accounts\AgreedTerms;
use Checkout\Accounts\ArticlesOfAssociation;
use Checkout\Accounts\ArticlesOfAssociationType;
use Checkout\Accounts\BankVerification;
use Checkout\Accounts\BankVerificationType;
use Checkout\Accounts\BusinessType;
use Checkout\Accounts\Citizenship;
use Checkout\Accounts\Company;
use Checkout\Accounts\CompanyPosition;
use Checkout\Accounts\CompanyVerification;
use Checkout\Accounts\CompanyVerificationType;
use Checkout\Accounts\ContactDetails;
use Checkout\Accounts\DateOfBirth;
use Checkout\Accounts\DateOfIncorporation;
use Checkout\Accounts\Document;
use Checkout\Accounts\EntityEmailAddresses;
use Checkout\Accounts\EntityRoles;
use Checkout\Accounts\FinancialStatements;
use Checkout\Accounts\FinancialStatementsType;
use Checkout\Accounts\FinancialVerification;
use Checkout\Accounts\FinancialVerificationType;
use Checkout\Accounts\NationalIdType;
use Checkout\Accounts\OnboardEntityRequest;
use Checkout\Accounts\OnboardSubEntityDocuments;
use Checkout\Accounts\PlaceOfBirth;
use Checkout\Accounts\ProcessingDetails;
use Checkout\Accounts\ProcessingDetailsAch;
use Checkout\Accounts\ProcessingDetailsPayments;
use Checkout\Accounts\Profile;
use Checkout\Accounts\ProofOfLegality;
use Checkout\Accounts\ProofOfLegalityType;
use Checkout\Accounts\ProofOfPrincipalAddress;
use Checkout\Accounts\ProofOfPrincipalAddressType;
use Checkout\Accounts\Representative;
use Checkout\Accounts\RepresentativeIndividual;
use Checkout\Accounts\ShareholderStructure;
use Checkout\Accounts\ShareholderStructureType;
use Checkout\Accounts\TaxVerification;
use Checkout\Accounts\TaxVerificationType;
use Checkout\Common\Address;
use Checkout\Common\Country;
use Checkout\Common\Currency;
use Checkout\Common\DocumentType;
use Checkout\Common\Phone;
use Checkout\JsonSerializer;
use PHPUnit\Framework\TestCase;

class OnboardEntityRequestSerializationTest extends TestCase
{
    // File IDs in the spec format ^file_[a-z2-7]{26}$, keyed by the document field they are sent on.
    private const FILE_IDS = array(
        "identity_verification" => "file_identityverificationaaaaaa",
        "identity_back" => "file_identitybackaaaaaaaaaaaaaa",
        "company_verification" => "file_companyverificationaaaaaaa",
        "tax_verification" => "file_taxverificationaaaaaaaaaaa",
        "articles_of_association" => "file_articlesofassociationaaaaa",
        "shareholder_structure" => "file_shareholderstructureaaaaaa",
        "bank_verification" => "file_bankverificationaaaaaaaaaa",
        "financial_statements" => "file_financialstatementsaaaaaaa",
        "financial_verification" => "file_financialverificationaaaaa",
        "proof_of_principal_address" => "file_proofofprincipaladdressaaa",
        "proof_of_legality" => "file_proofoflegalityaaaaaaaaaaa",
        "additional_document1" => "file_additionaldocumentoneaaaaa",
        "additional_document2" => "file_additionaldocumenttwoaaaaa",
        "additional_document3" => "file_additionaldocumentthreeaaa",
    );

    /**
     * The US ISV Seller Company (3.0) and US ISV Seller Sole Trader (3.0) request examples,
     * verbatim from components.schemas["USISVSellerCompany3-0"].example and
     * components.schemas["USISVSellerSoleTrader3-0"].example. Kept as raw JSON so a key renamed
     * in the spec or in the models shows up as a test failure.
     */
    private const US_ISV_SELLER_COMPANY_SWAGGER_EXAMPLE = <<<'JSON'
{
    "reference": "isv-seller-example001",
    "agreed_terms": {
        "date": "2026-07-02T10:30:00.0000000+00:00",
        "ip_address": "8.8.8.8",
        "name": "Toby Arden",
        "email": "toby.arden@example.com",
        "version": "cko-platform-terms-1.0.0"
    },
    "seller_category": "cat_retail_001",
    "processing_details": {
        "annual_processing_volume": 1000,
        "average_transaction_value": 2000,
        "average_order_fulfillment_time": 3,
        "target_countries": [
            "US"
        ],
        "currency": "USD",
        "payments": {
            "ach": {
                "annual_ach_volume": 100000,
                "average_ach_transaction_size": 5000,
                "estimated_monthly_credit_volume": 50000,
                "average_credit_amount": 2500
            }
        }
    },
    "contact_details": {
        "phone": {
            "number": "4155678900",
            "country_code": "US"
        },
        "email_addresses": {
            "primary": "toby.arden@example.com",
            "pci_compliance_contact": "pci.contact@example.com"
        }
    },
    "profile": {
        "urls": [
            "https://www.isv-seller-example.com"
        ],
        "mccs": [
            "5551"
        ],
        "holding_currencies": [
            "USD"
        ],
        "default_holding_currency": "USD"
    },
    "company": {
        "business_registration_number": "12-3456789",
        "business_type": "private_corporation",
        "legal_name": "ISV Seller Example Inc",
        "trading_name": "ISV Seller Example",
        "registered_address": {
            "address_line1": "123 Main Street",
            "city": "San Francisco",
            "state": "CA",
            "zip": "94105",
            "country": "US"
        },
        "principal_address": {
            "address_line1": "123 Main Street",
            "city": "San Francisco",
            "state": "CA",
            "zip": "94105",
            "country": "US"
        },
        "date_of_incorporation": {
            "year": 2025,
            "month": 10,
            "day": 1
        },
        "representatives": [
            {
                "roles": [
                    "ubo",
                    "control_person"
                ],
                "ownership_percentage": 25,
                "company_position": "ceo",
                "individual": {
                    "first_name": "Toby",
                    "last_name": "Arden",
                    "email_address": "toby.arden@example.com",
                    "national_id_type": "ssn",
                    "national_id_number": "123456789",
                    "date_of_birth": {
                        "day": 15,
                        "month": 1,
                        "year": 1990
                    },
                    "place_of_birth": {
                        "country": "US"
                    },
                    "citizenships": [
                        {
                            "country": "US"
                        }
                    ],
                    "phone": {
                        "country_code": "US",
                        "number": "4155678901"
                    },
                    "address": {
                        "address_line1": "123 Main Street",
                        "city": "San Francisco",
                        "state": "CA",
                        "zip": "94105",
                        "country": "US"
                    }
                }
            },
            {
                "roles": [
                    "authorised_signatory"
                ],
                "individual": {
                    "first_name": "Alex",
                    "last_name": "Morgan",
                    "email_address": "alex.morgan@example.com",
                    "national_id_type": "ssn",
                    "national_id_number": "987654321",
                    "date_of_birth": {
                        "day": 22,
                        "month": 6,
                        "year": 1985
                    },
                    "place_of_birth": {
                        "country": "US"
                    },
                    "citizenships": [
                        {
                            "country": "US"
                        }
                    ],
                    "phone": {
                        "country_code": "US",
                        "number": "4155678902"
                    },
                    "address": {
                        "address_line1": "123 Main Street",
                        "city": "San Francisco",
                        "state": "CA",
                        "zip": "94105",
                        "country": "US"
                    }
                }
            }
        ]
    }
}
JSON;

    private const US_ISV_SELLER_SOLE_TRADER_SWAGGER_EXAMPLE = <<<'JSON'
{
    "reference": "isv-sole-trader-example001",
    "agreed_terms": {
        "date": "2026-07-02T10:30:00.0000000+00:00",
        "ip_address": "8.8.8.8",
        "name": "Hannah Bret",
        "email": "hannah.bret@example.com",
        "version": "cko-platform-terms-1.0.0"
    },
    "seller_category": "cat_retail_001",
    "processing_details": {
        "annual_processing_volume": 1000,
        "average_transaction_value": 2000,
        "average_order_fulfillment_time": 3,
        "target_countries": [
            "US"
        ],
        "currency": "USD",
        "payments": {
            "ach": {
                "annual_ach_volume": 100000,
                "average_ach_transaction_size": 5000,
                "estimated_monthly_credit_volume": 50000,
                "average_credit_amount": 2500
            }
        }
    },
    "contact_details": {
        "phone": {
            "number": "4155678900",
            "country_code": "US"
        },
        "email_addresses": {
            "primary": "hannah.bret@example.com",
            "pci_compliance_contact": "pci.contact@example.com"
        }
    },
    "profile": {
        "urls": [
            "https://www.isv-sole-trader-example.com"
        ],
        "mccs": [
            "5551"
        ],
        "holding_currencies": [
            "USD"
        ],
        "default_holding_currency": "USD"
    },
    "company": {
        "business_type": "individual_or_sole_proprietorship",
        "is_registered_company": false,
        "trading_name": "Hannah's Goods",
        "date_of_incorporation": {
            "year": 2025,
            "month": 10,
            "day": 1
        },
        "principal_address": {
            "address_line1": "123 Main Street",
            "city": "San Francisco",
            "state": "CA",
            "zip": "94105",
            "country": "US"
        },
        "representatives": [
            {
                "roles": [
                    "ubo"
                ],
                "ownership_percentage": 100,
                "individual": {
                    "first_name": "Hannah",
                    "last_name": "Bret",
                    "email_address": "hannah.bret@example.com",
                    "national_id_type": "ssn",
                    "national_id_number": "123456789",
                    "date_of_birth": {
                        "day": 15,
                        "month": 1,
                        "year": 1990
                    },
                    "place_of_birth": {
                        "country": "US"
                    },
                    "citizenships": [
                        {
                            "country": "US"
                        }
                    ],
                    "phone": {
                        "country_code": "US",
                        "number": "4155678901"
                    },
                    "address": {
                        "address_line1": "123 Main Street",
                        "city": "San Francisco",
                        "state": "CA",
                        "zip": "94105",
                        "country": "US"
                    }
                }
            }
        ]
    }
}
JSON;

    public function testOnboardEntityRequestRoundTrip()
    {
        $agreedTerms = new AgreedTerms();
        $agreedTerms->date = "2026-07-20T10:00:00Z";
        $agreedTerms->ip_address = "203.0.113.42";
        $agreedTerms->name = "John Representative";
        $agreedTerms->email = "john@example.com";
        $agreedTerms->version = "1.0";

        $request = new OnboardEntityRequest();
        $request->reference = "ref_123";
        $request->seller_category = "cat_electronics";
        $request->is_draft = true;
        $request->agreed_terms = $agreedTerms;
        $request->documents = $this->buildDocuments();

        $decoded = json_decode((new JsonSerializer())->serialize($request), true);

        $this->assertSame("ref_123", $decoded['reference']);
        $this->assertSame("cat_electronics", $decoded['seller_category']);
        $this->assertTrue($decoded['is_draft']);
        $this->assertSame("john@example.com", $decoded['agreed_terms']['email']);
        $this->assertSame("2026-07-20T10:00:00Z", $decoded['agreed_terms']['date']);
    }

    public function testDocumentsRoundTripCoversEveryField()
    {
        $decoded = json_decode((new JsonSerializer())->serialize($this->buildDocuments()), true);

        // Every typed property of OnboardSubEntityDocuments must serialize with its spec type + front.
        $expectedTypes = array(
            "identity_verification" => "passport",
            "company_verification" => "incorporation_document",
            "tax_verification" => "ein_letter",
            "articles_of_association" => "articles_of_association",
            "shareholder_structure" => "certified_shareholder_structure",
            "bank_verification" => "bank_statement",
            "financial_statements" => "financial_statements",
            "financial_verification" => "financial_statement",
            "proof_of_principal_address" => "proof_of_address",
            "proof_of_legality" => "proof_of_legality",
        );

        foreach ($expectedTypes as $field => $type) {
            $this->assertArrayHasKey($field, $decoded, "documents.$field must serialize");
            $this->assertSame($type, $decoded[$field]['type'], "documents.$field.type");
            $this->assertSame(self::FILE_IDS[$field], $decoded[$field]['front'], "documents.$field.front");
        }

        // identity_verification is the only top-level document with a back side.
        $this->assertSame(self::FILE_IDS["identity_back"], $decoded['identity_verification']['back']);
        foreach (array_keys($expectedTypes) as $field) {
            if ($field !== "identity_verification") {
                $this->assertArrayNotHasKey("back", $decoded[$field], "documents.$field has no back");
            }
        }

        // The additional documents carry a file ID only: the API defines no type for them.
        foreach (array("additional_document1", "additional_document2", "additional_document3") as $field) {
            $this->assertSame(array("front" => self::FILE_IDS[$field]), $decoded[$field], "documents.$field");
        }
    }

    public function testUsIsvSellerCompanyMatchesSwaggerExample()
    {
        $dateOfIncorporation = new DateOfIncorporation();
        $dateOfIncorporation->year = 2025;
        $dateOfIncorporation->month = 10;
        $dateOfIncorporation->day = 1;

        $controlPerson = new Representative();
        $controlPerson->roles = array(EntityRoles::$ubo, EntityRoles::$control_person);
        $controlPerson->ownership_percentage = 25;
        $controlPerson->company_position = CompanyPosition::$ceo;
        $controlPerson->individual = $this->usIsvRepresentativeIndividual(
            "Toby",
            "Arden",
            "toby.arden@example.com",
            "123456789",
            $this->dateOfBirth(15, 1, 1990),
            "4155678901"
        );

        $signatory = new Representative();
        $signatory->roles = array(EntityRoles::$authorised_signatory);
        $signatory->individual = $this->usIsvRepresentativeIndividual(
            "Alex",
            "Morgan",
            "alex.morgan@example.com",
            "987654321",
            $this->dateOfBirth(22, 6, 1985),
            "4155678902"
        );

        $company = new Company();
        $company->business_registration_number = "12-3456789";
        $company->business_type = BusinessType::$private_corporation;
        $company->legal_name = "ISV Seller Example Inc";
        $company->trading_name = "ISV Seller Example";
        $company->registered_address = $this->usIsvAddress();
        $company->principal_address = $this->usIsvAddress();
        $company->date_of_incorporation = $dateOfIncorporation;
        $company->representatives = array($controlPerson, $signatory);

        $request = new OnboardEntityRequest();
        $request->reference = "isv-seller-example001";
        $request->agreed_terms = $this->usIsvAgreedTerms("Toby Arden", "toby.arden@example.com");
        $request->seller_category = "cat_retail_001";
        $request->processing_details = $this->usIsvProcessingDetails();
        $request->contact_details = $this->usIsvContactDetails("toby.arden@example.com");
        $request->profile = $this->usIsvProfile("https://www.isv-seller-example.com");
        $request->company = $company;

        $this->assertEquals(
            json_decode(self::US_ISV_SELLER_COMPANY_SWAGGER_EXAMPLE, true),
            json_decode((new JsonSerializer())->serialize($request), true)
        );
    }

    public function testUsIsvSellerSoleTraderMatchesSwaggerExample()
    {
        $dateOfIncorporation = new DateOfIncorporation();
        $dateOfIncorporation->year = 2025;
        $dateOfIncorporation->month = 10;
        $dateOfIncorporation->day = 1;

        $owner = new Representative();
        $owner->roles = array(EntityRoles::$ubo);
        $owner->ownership_percentage = 100;
        $owner->individual = $this->usIsvRepresentativeIndividual(
            "Hannah",
            "Bret",
            "hannah.bret@example.com",
            "123456789",
            $this->dateOfBirth(15, 1, 1990),
            "4155678901"
        );

        $company = new Company();
        $company->business_type = BusinessType::$individual_or_sole_proprietorship;
        $company->is_registered_company = false;
        $company->trading_name = "Hannah's Goods";
        $company->date_of_incorporation = $dateOfIncorporation;
        $company->principal_address = $this->usIsvAddress();
        $company->representatives = array($owner);

        $request = new OnboardEntityRequest();
        $request->reference = "isv-sole-trader-example001";
        $request->agreed_terms = $this->usIsvAgreedTerms("Hannah Bret", "hannah.bret@example.com");
        $request->seller_category = "cat_retail_001";
        $request->processing_details = $this->usIsvProcessingDetails();
        $request->contact_details = $this->usIsvContactDetails("hannah.bret@example.com");
        $request->profile = $this->usIsvProfile("https://www.isv-sole-trader-example.com");
        $request->company = $company;

        $decoded = json_decode((new JsonSerializer())->serialize($request), true);

        $this->assertEquals(json_decode(self::US_ISV_SELLER_SOLE_TRADER_SWAGGER_EXAMPLE, true), $decoded);
        // false is a value the API requires here, so it must be sent, not dropped like null.
        $this->assertArrayHasKey("is_registered_company", $decoded["company"]);
        $this->assertFalse($decoded["company"]["is_registered_company"]);
    }

    private function buildDocuments()
    {
        $documents = new OnboardSubEntityDocuments();

        $identity = new Document();
        $identity->type = DocumentType::$passport;
        $identity->front = self::FILE_IDS["identity_verification"];
        $identity->back = self::FILE_IDS["identity_back"];
        $documents->identity_verification = $identity;

        $companyVerification = new CompanyVerification();
        $companyVerification->type = CompanyVerificationType::$incorporation_document;
        $companyVerification->front = self::FILE_IDS["company_verification"];
        $documents->company_verification = $companyVerification;

        $taxVerification = new TaxVerification();
        $taxVerification->type = TaxVerificationType::$ein_letter;
        $taxVerification->front = self::FILE_IDS["tax_verification"];
        $documents->tax_verification = $taxVerification;

        $articlesOfAssociation = new ArticlesOfAssociation();
        $articlesOfAssociation->type = ArticlesOfAssociationType::$articles_of_association;
        $articlesOfAssociation->front = self::FILE_IDS["articles_of_association"];
        $documents->articles_of_association = $articlesOfAssociation;

        $shareholderStructure = new ShareholderStructure();
        $shareholderStructure->type = ShareholderStructureType::$certified_shareholder_structure;
        $shareholderStructure->front = self::FILE_IDS["shareholder_structure"];
        $documents->shareholder_structure = $shareholderStructure;

        $bankVerification = new BankVerification();
        $bankVerification->type = BankVerificationType::$bank_statement;
        $bankVerification->front = self::FILE_IDS["bank_verification"];
        $documents->bank_verification = $bankVerification;

        $financialStatements = new FinancialStatements();
        $financialStatements->type = FinancialStatementsType::$financial_statements;
        $financialStatements->front = self::FILE_IDS["financial_statements"];
        $documents->financial_statements = $financialStatements;

        $financialVerification = new FinancialVerification();
        $financialVerification->type = FinancialVerificationType::$financial_statement;
        $financialVerification->front = self::FILE_IDS["financial_verification"];
        $documents->financial_verification = $financialVerification;

        $proofOfPrincipalAddress = new ProofOfPrincipalAddress();
        $proofOfPrincipalAddress->type = ProofOfPrincipalAddressType::$proof_of_address;
        $proofOfPrincipalAddress->front = self::FILE_IDS["proof_of_principal_address"];
        $documents->proof_of_principal_address = $proofOfPrincipalAddress;

        $proofOfLegality = new ProofOfLegality();
        $proofOfLegality->type = ProofOfLegalityType::$proof_of_legality;
        $proofOfLegality->front = self::FILE_IDS["proof_of_legality"];
        $documents->proof_of_legality = $proofOfLegality;

        $documents->additional_document1 = $this->additionalDocument(self::FILE_IDS["additional_document1"]);
        $documents->additional_document2 = $this->additionalDocument(self::FILE_IDS["additional_document2"]);
        $documents->additional_document3 = $this->additionalDocument(self::FILE_IDS["additional_document3"]);

        return $documents;
    }

    private function additionalDocument($front)
    {
        $document = new AdditionalDocument();
        $document->front = $front;
        return $document;
    }

    private function usIsvAddress()
    {
        $address = new Address();
        $address->address_line1 = "123 Main Street";
        $address->city = "San Francisco";
        $address->state = "CA";
        $address->zip = "94105";
        $address->country = Country::$US;
        return $address;
    }

    private function usIsvPhone($number)
    {
        $phone = new Phone();
        $phone->country_code = Country::$US;
        $phone->number = $number;
        return $phone;
    }

    private function dateOfBirth($day, $month, $year)
    {
        $dateOfBirth = new DateOfBirth();
        $dateOfBirth->day = $day;
        $dateOfBirth->month = $month;
        $dateOfBirth->year = $year;
        return $dateOfBirth;
    }

    private function usIsvRepresentativeIndividual($firstName, $lastName, $email, $ssn, $dateOfBirth, $phoneNumber)
    {
        $placeOfBirth = new PlaceOfBirth();
        $placeOfBirth->country = Country::$US;

        // The spec examples send citizenships[].country only, with no type.
        $citizenship = new Citizenship();
        $citizenship->country = Country::$US;

        $individual = new RepresentativeIndividual();
        $individual->first_name = $firstName;
        $individual->last_name = $lastName;
        $individual->email_address = $email;
        $individual->national_id_type = NationalIdType::$ssn;
        $individual->national_id_number = $ssn;
        $individual->date_of_birth = $dateOfBirth;
        $individual->place_of_birth = $placeOfBirth;
        $individual->citizenships = array($citizenship);
        $individual->phone = $this->usIsvPhone($phoneNumber);
        $individual->address = $this->usIsvAddress();
        return $individual;
    }

    private function usIsvAgreedTerms($name, $email)
    {
        $agreedTerms = new AgreedTerms();
        $agreedTerms->date = "2026-07-02T10:30:00.0000000+00:00";
        $agreedTerms->ip_address = "8.8.8.8";
        $agreedTerms->name = $name;
        $agreedTerms->email = $email;
        $agreedTerms->version = "cko-platform-terms-1.0.0";
        return $agreedTerms;
    }

    private function usIsvProcessingDetails()
    {
        $ach = new ProcessingDetailsAch();
        $ach->annual_ach_volume = 100000;
        $ach->average_ach_transaction_size = 5000;
        $ach->estimated_monthly_credit_volume = 50000;
        $ach->average_credit_amount = 2500;

        $payments = new ProcessingDetailsPayments();
        $payments->ach = $ach;

        $processingDetails = new ProcessingDetails();
        $processingDetails->annual_processing_volume = 1000;
        $processingDetails->average_transaction_value = 2000;
        $processingDetails->average_order_fulfillment_time = 3;
        $processingDetails->target_countries = array(Country::$US);
        $processingDetails->currency = Currency::$USD;
        $processingDetails->payments = $payments;
        return $processingDetails;
    }

    private function usIsvContactDetails($primaryEmail)
    {
        $emailAddresses = new EntityEmailAddresses();
        $emailAddresses->primary = $primaryEmail;
        $emailAddresses->pci_compliance_contact = "pci.contact@example.com";

        $contactDetails = new ContactDetails();
        $contactDetails->phone = $this->usIsvPhone("4155678900");
        $contactDetails->email_addresses = $emailAddresses;
        return $contactDetails;
    }

    private function usIsvProfile($url)
    {
        $profile = new Profile();
        $profile->urls = array($url);
        $profile->mccs = array("5551");
        $profile->holding_currencies = array(Currency::$USD);
        $profile->default_holding_currency = Currency::$USD;
        return $profile;
    }
}
