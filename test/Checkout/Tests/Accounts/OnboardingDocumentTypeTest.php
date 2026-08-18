<?php

namespace Checkout\Tests\Accounts;

use Checkout\Accounts\ArticlesOfAssociationType;
use Checkout\Accounts\BankVerificationType;
use Checkout\Accounts\ShareholderStructureType;
use Checkout\Common\DocumentType;
use PHPUnit\Framework\TestCase;

/**
 * The onboarding document types that had no constants: bank_verification,
 * articles_of_association and shareholder_structure. All three were typed as the generic
 * Document, whose docblock points at Common\DocumentType, so a caller following the docblock
 * looked for these values in the identity enum and concluded they were missing.
 *
 * Note bank_statement is also the only accepted payment instrument document type, but the
 * spec keeps those as two separate enums in two separate places (see InstrumentDocumentType,
 * added on its own branch). Same value, different enum, kept apart on purpose so a future
 * change to one does not silently change the other.
 */
class OnboardingDocumentTypeTest extends TestCase
{
    public function testExposesTheOnboardingDocumentTypes()
    {
        $this->assertSame("bank_statement", BankVerificationType::$bank_statement);
        $this->assertSame("memorandum_of_association", ArticlesOfAssociationType::$memorandum_of_association);
        $this->assertSame("articles_of_association", ArticlesOfAssociationType::$articles_of_association);
        $this->assertSame("certified_shareholder_structure", ShareholderStructureType::$certified_shareholder_structure);
    }

    /**
     * These belong to their own enums, not to the identity document one. This is the assertion
     * that stops them being merged into Common\DocumentType the next time someone reports one
     * of the values as missing from it.
     */
    public function testKeepsOnboardingTypesOutOfTheIdentityDocumentType()
    {
        $identity = array(
            DocumentType::$passport,
            DocumentType::$national_identity_card,
            DocumentType::$driving_license,
            DocumentType::$citizen_card,
            DocumentType::$residence_permit,
            DocumentType::$electoral_id,
        );

        $this->assertNotContains("bank_statement", $identity);
        $this->assertNotContains("articles_of_association", $identity);
        $this->assertNotContains("certified_shareholder_structure", $identity);
    }
}
