<?php

namespace Checkout\Tests\Identities\AddressDocumentVerification;

use Checkout\Identities\AddressDocumentVerification\Requests\AddressDocumentVerificationRequest;
use Checkout\Identities\AddressDocumentVerification\Requests\AddressDocumentVerificationAttemptRequest;
use Checkout\Identities\Entities\DeclaredData;
use Checkout\JsonSerializer;
use PHPUnit\Framework\TestCase;

class AddressDocumentVerificationSerializationTest extends TestCase
{
    public function testAddressDocumentVerificationRequestRoundTrip()
    {
        $declaredData = new DeclaredData();
        $declaredData->name = "Hannah Bret";

        $request = new AddressDocumentVerificationRequest();
        $request->applicant_id = "aplt_tkoi5db4hryu5cei5vwoabr7we";
        $request->user_journey_id = "usj_tkoi5db4hryu5cei5vwoabr7we";
        $request->declared_data = $declaredData;

        $decoded = json_decode((new JsonSerializer())->serialize($request), true);

        $this->assertSame("aplt_tkoi5db4hryu5cei5vwoabr7we", $decoded['applicant_id']);
        $this->assertSame("usj_tkoi5db4hryu5cei5vwoabr7we", $decoded['user_journey_id']);
        $this->assertSame("Hannah Bret", $decoded['declared_data']['name']);
    }

    public function testAddressDocumentVerificationRequestFromSwaggerExample()
    {
        $json = '{'
            . '"applicant_id":"aplt_tkoi5db4hryu5cei5vwoabr7we",'
            . '"user_journey_id":"usj_tkoi5db4hryu5cei5vwoabr7we",'
            . '"declared_data":{"name":"Hannah Bret"}'
            . '}';

        $decoded = json_decode($json, true);

        $this->assertSame("aplt_tkoi5db4hryu5cei5vwoabr7we", $decoded['applicant_id']);
        $this->assertSame("usj_tkoi5db4hryu5cei5vwoabr7we", $decoded['user_journey_id']);
        $this->assertSame("Hannah Bret", $decoded['declared_data']['name']);
    }

    public function testAttemptRequestRoundTrip()
    {
        $request = new AddressDocumentVerificationAttemptRequest();
        $request->document = "base64-encoded-document-image-data";

        $decoded = json_decode((new JsonSerializer())->serialize($request), true);

        $this->assertSame("base64-encoded-document-image-data", $decoded['document']);
    }
}
