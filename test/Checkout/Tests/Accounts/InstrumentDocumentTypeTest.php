<?php

namespace Checkout\Tests\Accounts;

use Checkout\Accounts\InstrumentDocument;
use Checkout\Accounts\InstrumentDocumentType;
use Checkout\Common\DocumentType;
use Checkout\Tests\UnitTestFixture;

class InstrumentDocumentTypeTest extends UnitTestFixture
{
    /**
     * @test
     */
    public function shouldExposeBankStatementForInstrumentDocuments()
    {
        $document = new InstrumentDocument();
        $document->type = InstrumentDocumentType::$bank_statement;
        $document->file_id = "file_wxglze3wwywujg4nna5fb7ldli";

        $this->assertEquals("bank_statement", $document->type);
    }

    /**
     * bank_statement belongs to the instrument document enum, not to the identity document one.
     * The API keeps them separate, and this test is what stops them being merged again the next
     * time someone reports the value as missing from DocumentType.
     *
     * @test
     */
    public function shouldKeepBankStatementOutOfTheIdentityDocumentType()
    {
        $identityTypes = [
            DocumentType::$passport,
            DocumentType::$national_identity_card,
            DocumentType::$driving_license,
            DocumentType::$citizen_card,
            DocumentType::$residence_permit,
            DocumentType::$electoral_id,
        ];

        $this->assertNotContains("bank_statement", $identityTypes);
    }
}
