<?php

namespace Checkout\Tests\Accounts;

use Checkout\Accounts\Files\Entities\FilePurpose;
use Checkout\Accounts\Files\Requests\UploadFileRequest;
use Checkout\JsonSerializer;
use PHPUnit\Framework\TestCase;

class UploadFileRequestSerializationTest extends TestCase
{
    public function testUploadFileRequestSerializesPurpose()
    {
        $request = new UploadFileRequest();
        $request->purpose = FilePurpose::$identity_verification;

        $this->assertSame('{"purpose":"identity_verification"}', (new JsonSerializer())->serialize($request));
    }

    /**
     * The 14 values of PlatformsFileUpload.purpose in the spec. The spec schema named FilePurpose
     * is the disputes enum, not this one.
     */
    public function testFilePurposeValuesMatchSwagger()
    {
        $this->assertSame("additional_document", FilePurpose::$additional_document);
        $this->assertSame("articles_of_association", FilePurpose::$articles_of_association);
        $this->assertSame("bank_verification", FilePurpose::$bank_verification);
        $this->assertSame("certified_authorised_signatory", FilePurpose::$certified_authorised_signatory);
        $this->assertSame("company_ownership", FilePurpose::$company_ownership);
        $this->assertSame("company_verification", FilePurpose::$company_verification);
        $this->assertSame("financial_verification", FilePurpose::$financial_verification);
        $this->assertSame("identity_verification", FilePurpose::$identity_verification);
        $this->assertSame("proof_of_legality", FilePurpose::$proof_of_legality);
        $this->assertSame("proof_of_principal_address", FilePurpose::$proof_of_principal_address);
        $this->assertSame("shareholder_structure", FilePurpose::$shareholder_structure);
        $this->assertSame("tax_verification", FilePurpose::$tax_verification);
        $this->assertSame("proof_of_residential_address", FilePurpose::$proof_of_residential_address);
        $this->assertSame("proof_of_registration", FilePurpose::$proof_of_registration);
    }
}
