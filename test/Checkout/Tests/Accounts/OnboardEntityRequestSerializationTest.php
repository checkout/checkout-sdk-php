<?php

namespace Checkout\Tests\Accounts;

use Checkout\Accounts\AgreedTerms;
use Checkout\Accounts\CompanyVerification;
use Checkout\Accounts\Document;
use Checkout\Accounts\OnboardEntityRequest;
use Checkout\Accounts\OnboardSubEntityDocuments;
use Checkout\Accounts\TaxVerification;
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

        // Every property of OnboardSubEntityDocuments must serialize with its type + front.
        $expectedTypes = array(
            "identity_verification" => "identity_card",
            "company_verification" => "certificate_of_incorporation",
            "tax_verification" => "tax_document",
            "articles_of_association" => "articles_of_association",
            "shareholder_structure" => "certified_shareholder_structure",
            "bank_verification" => "bank_statement",
            "financial_statements" => "financial_statements",
            "financial_verification" => "financial_verification",
            "proof_of_principal_address" => "utility_bill",
            "proof_of_legality" => "proof_of_legality",
            "additional_document1" => "additional_document",
            "additional_document2" => "additional_document",
            "additional_document3" => "additional_document",
        );

        foreach ($expectedTypes as $field => $type) {
            $this->assertArrayHasKey($field, $decoded, "documents.$field must serialize");
            $this->assertSame($type, $decoded[$field]['type'], "documents.$field.type");
            $this->assertSame("file_$field", $decoded[$field]['front'], "documents.$field.front");
        }

        // identity_verification is a Document (supports back); the others carry type + front only.
        $this->assertSame("back_id", $decoded['identity_verification']['back']);
    }

    private function buildDocuments()
    {
        $documents = new OnboardSubEntityDocuments();

        $identity = new Document();
        $identity->type = "identity_card";
        $identity->front = "file_identity_verification";
        $identity->back = "back_id";
        $documents->identity_verification = $identity;

        $companyVerification = new CompanyVerification();
        $companyVerification->type = "certificate_of_incorporation";
        $companyVerification->front = "file_company_verification";
        $documents->company_verification = $companyVerification;

        $taxVerification = new TaxVerification();
        $taxVerification->type = "tax_document";
        $taxVerification->front = "file_tax_verification";
        $documents->tax_verification = $taxVerification;

        $documents->articles_of_association = $this->document("articles_of_association", "articles_of_association");
        $documents->shareholder_structure = $this->document("shareholder_structure", "certified_shareholder_structure");
        $documents->bank_verification = $this->document("bank_verification", "bank_statement");
        $documents->financial_statements = $this->document("financial_statements", "financial_statements");
        $documents->financial_verification = $this->document("financial_verification", "financial_verification");
        $documents->proof_of_principal_address = $this->document("proof_of_principal_address", "utility_bill");
        $documents->proof_of_legality = $this->document("proof_of_legality", "proof_of_legality");
        $documents->additional_document1 = $this->document("additional_document1", "additional_document");
        $documents->additional_document2 = $this->document("additional_document2", "additional_document");
        $documents->additional_document3 = $this->document("additional_document3", "additional_document");

        return $documents;
    }

    private function document($field, $type)
    {
        $document = new Document();
        $document->type = $type;
        $document->front = "file_$field";
        return $document;
    }
}
