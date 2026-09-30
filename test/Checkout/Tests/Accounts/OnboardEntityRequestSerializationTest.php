<?php

namespace Checkout\Tests\Accounts;

use Checkout\Accounts\AdditionalDocument;
use Checkout\Accounts\AgreedTerms;
use Checkout\Accounts\ArticlesOfAssociation;
use Checkout\Accounts\ArticlesOfAssociationType;
use Checkout\Accounts\BankVerification;
use Checkout\Accounts\BankVerificationType;
use Checkout\Accounts\CompanyVerification;
use Checkout\Accounts\CompanyVerificationType;
use Checkout\Accounts\Document;
use Checkout\Accounts\FinancialStatements;
use Checkout\Accounts\FinancialStatementsType;
use Checkout\Accounts\FinancialVerification;
use Checkout\Accounts\FinancialVerificationType;
use Checkout\Accounts\OnboardEntityRequest;
use Checkout\Accounts\OnboardSubEntityDocuments;
use Checkout\Accounts\ProofOfLegality;
use Checkout\Accounts\ProofOfLegalityType;
use Checkout\Accounts\ProofOfPrincipalAddress;
use Checkout\Accounts\ProofOfPrincipalAddressType;
use Checkout\Accounts\ShareholderStructure;
use Checkout\Accounts\ShareholderStructureType;
use Checkout\Accounts\TaxVerification;
use Checkout\Accounts\TaxVerificationType;
use Checkout\Common\DocumentType;
use Checkout\JsonSerializer;
use PHPUnit\Framework\TestCase;

class OnboardEntityRequestSerializationTest extends TestCase
{
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
            $this->assertSame("file_$field", $decoded[$field]['front'], "documents.$field.front");
        }

        // identity_verification is the only top-level document with a back side.
        $this->assertSame("file_identity_back", $decoded['identity_verification']['back']);
        foreach (array_keys($expectedTypes) as $field) {
            if ($field !== "identity_verification") {
                $this->assertArrayNotHasKey("back", $decoded[$field], "documents.$field has no back");
            }
        }

        // The additional documents carry a file ID only: the API defines no type for them.
        foreach (array("additional_document1", "additional_document2", "additional_document3") as $field) {
            $this->assertSame(array("front" => "file_$field"), $decoded[$field], "documents.$field");
        }
    }

    private function buildDocuments()
    {
        $documents = new OnboardSubEntityDocuments();

        $identity = new Document();
        $identity->type = DocumentType::$passport;
        $identity->front = "file_identity_verification";
        $identity->back = "file_identity_back";
        $documents->identity_verification = $identity;

        $companyVerification = new CompanyVerification();
        $companyVerification->type = CompanyVerificationType::$incorporation_document;
        $companyVerification->front = "file_company_verification";
        $documents->company_verification = $companyVerification;

        $taxVerification = new TaxVerification();
        $taxVerification->type = TaxVerificationType::$ein_letter;
        $taxVerification->front = "file_tax_verification";
        $documents->tax_verification = $taxVerification;

        $articlesOfAssociation = new ArticlesOfAssociation();
        $articlesOfAssociation->type = ArticlesOfAssociationType::$articles_of_association;
        $articlesOfAssociation->front = "file_articles_of_association";
        $documents->articles_of_association = $articlesOfAssociation;

        $shareholderStructure = new ShareholderStructure();
        $shareholderStructure->type = ShareholderStructureType::$certified_shareholder_structure;
        $shareholderStructure->front = "file_shareholder_structure";
        $documents->shareholder_structure = $shareholderStructure;

        $bankVerification = new BankVerification();
        $bankVerification->type = BankVerificationType::$bank_statement;
        $bankVerification->front = "file_bank_verification";
        $documents->bank_verification = $bankVerification;

        $financialStatements = new FinancialStatements();
        $financialStatements->type = FinancialStatementsType::$financial_statements;
        $financialStatements->front = "file_financial_statements";
        $documents->financial_statements = $financialStatements;

        $financialVerification = new FinancialVerification();
        $financialVerification->type = FinancialVerificationType::$financial_statement;
        $financialVerification->front = "file_financial_verification";
        $documents->financial_verification = $financialVerification;

        $proofOfPrincipalAddress = new ProofOfPrincipalAddress();
        $proofOfPrincipalAddress->type = ProofOfPrincipalAddressType::$proof_of_address;
        $proofOfPrincipalAddress->front = "file_proof_of_principal_address";
        $documents->proof_of_principal_address = $proofOfPrincipalAddress;

        $proofOfLegality = new ProofOfLegality();
        $proofOfLegality->type = ProofOfLegalityType::$proof_of_legality;
        $proofOfLegality->front = "file_proof_of_legality";
        $documents->proof_of_legality = $proofOfLegality;

        $documents->additional_document1 = $this->additionalDocument("file_additional_document1");
        $documents->additional_document2 = $this->additionalDocument("file_additional_document2");
        $documents->additional_document3 = $this->additionalDocument("file_additional_document3");

        return $documents;
    }

    private function additionalDocument($front)
    {
        $document = new AdditionalDocument();
        $document->front = $front;
        return $document;
    }
}
