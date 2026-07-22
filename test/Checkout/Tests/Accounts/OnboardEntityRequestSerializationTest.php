<?php

namespace Checkout\Tests\Accounts;

use Checkout\Accounts\AgreedTerms;
use Checkout\Accounts\Document;
use Checkout\Accounts\OnboardEntityRequest;
use Checkout\Accounts\OnboardSubEntityDocuments;
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

    public function testDocumentsRoundTripIncludingFinancialStatements()
    {
        $decoded = json_decode((new JsonSerializer())->serialize($this->buildDocuments()), true);

        $this->assertSame("articles_of_association", $decoded['articles_of_association']['type']);
        $this->assertSame("file_1", $decoded['articles_of_association']['front']);
        $this->assertSame("certified_shareholder_structure", $decoded['shareholder_structure']['type']);
        $this->assertSame("financial_statements", $decoded['financial_statements']['type']);
        $this->assertSame("file_2", $decoded['financial_statements']['front']);
        $this->assertSame("financial_verification", $decoded['financial_verification']['type']);
        $this->assertSame("file_3", $decoded['financial_verification']['front']);
    }

    private function buildDocuments()
    {
        $articles = new Document();
        $articles->type = "articles_of_association";
        $articles->front = "file_1";

        $shareholder = new Document();
        $shareholder->type = "certified_shareholder_structure";
        $shareholder->front = "file_1";

        $financialStatements = new Document();
        $financialStatements->type = "financial_statements";
        $financialStatements->front = "file_2";

        $financialVerification = new Document();
        $financialVerification->type = "financial_verification";
        $financialVerification->front = "file_3";

        $documents = new OnboardSubEntityDocuments();
        $documents->articles_of_association = $articles;
        $documents->shareholder_structure = $shareholder;
        $documents->financial_statements = $financialStatements;
        $documents->financial_verification = $financialVerification;

        return $documents;
    }
}
