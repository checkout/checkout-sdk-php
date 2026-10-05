<?php

namespace Checkout\Tests\Accounts;

use Checkout\Accounts\BankVerification;
use Checkout\Accounts\BankVerificationType;
use Checkout\Accounts\BusinessType;
use Checkout\Accounts\CertifiedAuthorisedSignatory;
use Checkout\Accounts\CertifiedAuthorisedSignatoryType;
use Checkout\Accounts\Company;
use Checkout\Accounts\Document;
use Checkout\Accounts\EntityRoles;
use Checkout\Accounts\OnboardEntityRequest;
use Checkout\Accounts\OnboardSubEntityDocuments;
use Checkout\Accounts\ProofOfRegistration;
use Checkout\Accounts\ProofOfRegistrationType;
use Checkout\Accounts\ProofOfResidentialAddress;
use Checkout\Accounts\ProofOfResidentialAddressType;
use Checkout\Accounts\Representative;
use Checkout\Accounts\RepresentativeDocuments;
use Checkout\Accounts\RepresentativeIndividual;
use Checkout\Common\DocumentType;
use Checkout\JsonSerializer;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionProperty;

class RepresentativeDocumentsSerializationTest extends TestCase
{
    /**
     * Regression: EEA Sole Trader (3.0) needs proof_of_residential_address and proof_of_registration
     * on company.representatives[].documents. Neither was expressible on the representative model;
     * both existed only as upload purposes. Reported internally.
     */
    public function testEeaSoleTraderRepresentativeDocuments()
    {
        $representative = new Representative();
        $representative->individual = $this->buildIndividual();
        $representative->roles = array(EntityRoles::$ubo);
        $representative->documents = $this->buildEeaSoleTraderDocuments();

        $company = new Company();
        $company->trading_name = "Test Sole Trader";
        $company->business_type = BusinessType::$individual_or_sole_proprietorship;
        $company->representatives = array($representative);

        $bankVerification = new BankVerification();
        $bankVerification->type = BankVerificationType::$bank_statement;
        $bankVerification->front = "file_bankverificationaaaaaaaaaa";

        $request = new OnboardEntityRequest();
        $request->reference = "ref_sole_trader";
        $request->company = $company;
        $request->documents = new OnboardSubEntityDocuments();
        $request->documents->bank_verification = $bankVerification;

        $body = (new JsonSerializer())->serialize($request);
        $decoded = json_decode($body, true);

        $this->assertSame(
            array(
                "identity_verification" => array(
                    "type" => "passport",
                    "front" => "file_identityverificationaaaaaa",
                ),
                "proof_of_residential_address" => array(
                    "type" => "proof_of_address",
                    "front" => "file_proofofresidentialaddressa",
                ),
                "proof_of_registration" => array(
                    "type" => "extract_from_trade_register",
                    "front" => "file_proofofregistrationaaaaaaa",
                ),
            ),
            $decoded['company']['representatives'][0]['documents']
        );

        // The representative documents stay on the representative; the top level carries only
        // bank_verification.
        $this->assertSame(
            array("bank_verification" => array(
                "type" => "bank_statement",
                "front" => "file_bankverificationaaaaaaaaaa",
            )),
            $decoded['documents']
        );

        // Key-level check on the raw body, so a naming change cannot pass silently.
        $this->assertStringContainsString('"proof_of_residential_address":{', $body);
        $this->assertStringContainsString('"proof_of_registration":{', $body);
    }

    public function testCertifiedAuthorisedSignatoryOnCompanyRepresentative()
    {
        $signatory = new CertifiedAuthorisedSignatory();
        $signatory->type = CertifiedAuthorisedSignatoryType::$power_of_attorney;
        $signatory->front = "file_signatoryaaaaaaaaaaaaaaaaa";

        $documents = new RepresentativeDocuments();
        $documents->certified_authorised_signatory = $signatory;

        $representative = new Representative();
        $representative->roles = array(EntityRoles::$legal_representative);
        $representative->documents = $documents;

        $decoded = json_decode((new JsonSerializer())->serialize($representative), true);

        $this->assertSame(
            array("certified_authorised_signatory" => array(
                "type" => "power_of_attorney",
                "front" => "file_signatoryaaaaaaaaaaaaaaaaa",
            )),
            $decoded['documents']
        );
    }

    /**
     * These four are the only keys company.representatives[].documents defines in any v3.0
     * variant. The EEA, GB and US Company - Full (3.0) Person of Interest representatives and the
     * EEA, GB and US Sole Trader - Full (3.0) variants reject any other key
     * (additionalProperties: false), so a property added here by mistake would fail those requests.
     */
    public function testRepresentativeDocumentsDeclaresOnlyTheKeysTheApiAccepts()
    {
        $properties = array_map(
            function (ReflectionProperty $property) {
                return $property->getName();
            },
            (new ReflectionClass(RepresentativeDocuments::class))->getProperties()
        );

        $this->assertSame(
            array(
                "identity_verification",
                "certified_authorised_signatory",
                "proof_of_residential_address",
                "proof_of_registration",
            ),
            $properties
        );
    }

    /**
     * The typed model is additive: an associative array assigned to Representative::$documents,
     * as used before RepresentativeDocuments existed, still produces byte-identical JSON.
     */
    public function testAssociativeArrayAndTypedModelSerializeIdentically()
    {
        $typed = new Representative();
        $typed->roles = array(EntityRoles::$ubo);
        $typed->documents = $this->buildEeaSoleTraderDocuments();

        $array = new Representative();
        $array->roles = array(EntityRoles::$ubo);
        $array->documents = array(
            "identity_verification" => array(
                "type" => "passport",
                "front" => "file_identityverificationaaaaaa",
            ),
            "proof_of_residential_address" => array(
                "type" => "proof_of_address",
                "front" => "file_proofofresidentialaddressa",
            ),
            "proof_of_registration" => array(
                "type" => "extract_from_trade_register",
                "front" => "file_proofofregistrationaaaaaaa",
            ),
        );

        $serializer = new JsonSerializer();
        $this->assertSame($serializer->serialize($array), $serializer->serialize($typed));
    }

    private function buildEeaSoleTraderDocuments()
    {
        $identity = new Document();
        $identity->type = DocumentType::$passport;
        $identity->front = "file_identityverificationaaaaaa";

        $proofOfResidentialAddress = new ProofOfResidentialAddress();
        $proofOfResidentialAddress->type = ProofOfResidentialAddressType::$proof_of_address;
        $proofOfResidentialAddress->front = "file_proofofresidentialaddressa";

        $proofOfRegistration = new ProofOfRegistration();
        $proofOfRegistration->type = ProofOfRegistrationType::$extract_from_trade_register;
        $proofOfRegistration->front = "file_proofofregistrationaaaaaaa";

        $documents = new RepresentativeDocuments();
        $documents->identity_verification = $identity;
        $documents->proof_of_residential_address = $proofOfResidentialAddress;
        $documents->proof_of_registration = $proofOfRegistration;
        return $documents;
    }

    private function buildIndividual()
    {
        $individual = new RepresentativeIndividual();
        $individual->first_name = "Jane";
        $individual->last_name = "Doe";
        return $individual;
    }
}
