<?php

namespace Checkout\Tests\Accounts;

use Checkout\Accounts\ArticlesOfAssociationType;
use Checkout\Accounts\BankVerificationType;
use Checkout\Accounts\CertifiedAuthorisedSignatoryType;
use Checkout\Accounts\CompanyVerificationType;
use Checkout\Accounts\FinancialStatementsType;
use Checkout\Accounts\FinancialVerificationType;
use Checkout\Accounts\ProofOfLegalityType;
use Checkout\Accounts\ProofOfPrincipalAddressType;
use Checkout\Accounts\ProofOfRegistrationType;
use Checkout\Accounts\ProofOfResidentialAddressType;
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
        $this->assertSame("proof_of_address", ProofOfPrincipalAddressType::$proof_of_address);
        $this->assertSame("proof_of_legality", ProofOfLegalityType::$proof_of_legality);
        $this->assertSame("financial_statement", FinancialVerificationType::$financial_statement);
        $this->assertSame("financial_statements", FinancialStatementsType::$financial_statements);
        $this->assertSame("incorporation_document", CompanyVerificationType::$incorporation_document);
        $this->assertSame("articles_of_association", CompanyVerificationType::$articles_of_association);
    }

    /**
     * The identity_verification document types, the same six values in every variant.
     */
    public function testExposesTheIdentityDocumentTypes()
    {
        $this->assertSame("passport", DocumentType::$passport);
        $this->assertSame("national_identity_card", DocumentType::$national_identity_card);
        $this->assertSame("driving_license", DocumentType::$driving_license);
        $this->assertSame("citizen_card", DocumentType::$citizen_card);
        $this->assertSame("residence_permit", DocumentType::$residence_permit);
        $this->assertSame("electoral_id", DocumentType::$electoral_id);
    }

    /**
     * The document types of the representative documents (company.representatives[].documents).
     * proof_of_address appears on both ProofOfResidentialAddressType and ProofOfPrincipalAddressType
     * on purpose: the spec defines them as two enums on two different documents.
     */
    public function testExposesTheRepresentativeDocumentTypes()
    {
        $this->assertSame("proof_of_address", ProofOfResidentialAddressType::$proof_of_address);
        $this->assertSame("extract_from_trade_register", ProofOfRegistrationType::$extract_from_trade_register);
        $this->assertSame("other", ProofOfRegistrationType::$other);
        $this->assertSame("power_of_attorney", CertifiedAuthorisedSignatoryType::$power_of_attorney);
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
        $this->assertNotContains("proof_of_address", $identity);
        $this->assertNotContains("extract_from_trade_register", $identity);
        $this->assertNotContains("power_of_attorney", $identity);
    }
}
