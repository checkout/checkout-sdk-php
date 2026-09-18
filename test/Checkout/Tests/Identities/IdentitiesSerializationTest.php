<?php

namespace Checkout\Tests\Identities;

use Checkout\Identities\Entities\AttemptAssetsQueryFilter;
use Checkout\Identities\Entities\AttemptsQueryFilter;
use Checkout\Identities\Entities\ClientInformation;
use Checkout\Identities\Entities\DeclaredData;
use Checkout\Identities\Entities\IdentityDeclaredData;
use Checkout\Identities\Entities\IdentityVerificationClientInformation;
use Checkout\Identities\Entities\IdvAddress;
use Checkout\Identities\Entities\PhoneNumber;
use Checkout\Identities\FaceAuthentication\Requests\FaceAuthenticationAttemptRequest;
use Checkout\Identities\IdentityVerification\Requests\IdentityVerificationAndOpenRequest;
use Checkout\Identities\IdentityVerification\Requests\IdentityVerificationAttemptRequest;
use Checkout\Identities\IdentityVerification\Requests\IdentityVerificationRequest;
use Checkout\JsonSerializer;
use PHPUnit\Framework\TestCase;

class IdentitiesSerializationTest extends TestCase
{
    /**
     * @var JsonSerializer
     */
    private $serializer;

    /**
     * @before
     */
    public function init()
    {
        $this->serializer = new JsonSerializer();
    }

    public function testPhoneNumberSerializesBothProperties()
    {
        $phoneNumber = new PhoneNumber();
        $phoneNumber->country_code = "+33";
        $phoneNumber->number = "5555550102";

        $decoded = json_decode($this->serializer->serialize($phoneNumber), true);

        $this->assertSame(["country_code", "number"], array_keys($decoded));
        $this->assertSame("+33", $decoded["country_code"]);
        $this->assertSame("5555550102", $decoded["number"]);
    }

    public function testIdvAddressSerializesEveryProperty()
    {
        $address = new IdvAddress();
        $address->address_line1 = "123 Main Street";
        $address->address_line2 = "Flat 4";
        $address->city = "London";
        $address->state = "Greater London";
        $address->zip = "SW1A 1AA";
        $address->country = "GB";

        $decoded = json_decode($this->serializer->serialize($address), true);

        $this->assertSame(
            ["address_line1", "address_line2", "city", "state", "zip", "country"],
            array_keys($decoded)
        );
        $this->assertSame("123 Main Street", $decoded["address_line1"]);
        $this->assertSame("Flat 4", $decoded["address_line2"]);
        $this->assertSame("London", $decoded["city"]);
        $this->assertSame("Greater London", $decoded["state"]);
        $this->assertSame("SW1A 1AA", $decoded["zip"]);
        $this->assertSame("GB", $decoded["country"]);
    }

    public function testDeclaredDataSerializesNameAndBirthDate()
    {
        $declaredData = new DeclaredData();
        $declaredData->name = "Hannah Bret";
        $declaredData->birth_date = "1994-10-15";

        $decoded = json_decode($this->serializer->serialize($declaredData), true);

        $this->assertSame("Hannah Bret", $decoded["name"]);
        $this->assertSame("1994-10-15", $decoded["birth_date"]);
        $this->assertCount(2, $decoded);
    }

    public function testDeclaredDataOmitsUnsetBirthDate()
    {
        $declaredData = new DeclaredData();
        $declaredData->name = "Hannah Bret";

        $decoded = json_decode($this->serializer->serialize($declaredData), true);

        $this->assertSame(["name"], array_keys($decoded));
    }

    public function testIdentityDeclaredDataSerializesEveryProperty()
    {
        $decoded = json_decode($this->serializer->serialize($this->buildIdentityDeclaredData()), true);

        $this->assertSame("Hannah Bret", $decoded["name"]);
        $this->assertSame("1994-10-15", $decoded["birth_date"]);
        $this->assertSame("hannah.bret@example.com", $decoded["email"]);
        $this->assertSame("+33", $decoded["phone_number"]["country_code"]);
        $this->assertSame("5555550102", $decoded["phone_number"]["number"]);
        $this->assertSame("123 Main Street", $decoded["address"]["address_line1"]);
        $this->assertSame("GB", $decoded["address"]["country"]);
        $this->assertCount(5, $decoded);
    }

    public function testIdentityDeclaredDataRoundTrip()
    {
        $json = $this->serializer->serialize($this->buildIdentityDeclaredData());
        $decoded = $this->serializer->deserialize($json);

        $reserialized = $this->serializer->serialize($decoded);

        $this->assertSame(
            json_decode($json, true),
            json_decode($reserialized, true)
        );
    }

    public function testIdentityDeclaredDataOmitsUnsetEmail()
    {
        $declaredData = new IdentityDeclaredData();
        $declaredData->name = "Hannah Bret";

        $decoded = json_decode($this->serializer->serialize($declaredData), true);

        $this->assertSame(["name"], array_keys($decoded));
        $this->assertArrayNotHasKey("email", $decoded);
    }

    /**
     * The spec marks email nullable, so a response may carry an explicit null rather than omitting
     * the key. Deserialization must preserve that instead of dropping it.
     */
    public function testIdentityDeclaredDataReadsAnExplicitNullEmail()
    {
        $json = '{"name":"Hannah Bret","birth_date":"1994-10-15","email":null}';

        $decoded = $this->serializer->deserialize($json);

        $this->assertArrayHasKey("email", $decoded);
        $this->assertNull($decoded["email"]);
        $this->assertSame("Hannah Bret", $decoded["name"]);
    }

    public function testFaceAuthenticationClientInformationKeepsTheTwoFieldShape()
    {
        $clientInformation = new ClientInformation();
        $clientInformation->pre_selected_residence_country = "FR";
        $clientInformation->pre_selected_language = "en-US";

        $decoded = json_decode($this->serializer->serialize($clientInformation), true);

        $this->assertSame(
            ["pre_selected_residence_country", "pre_selected_language"],
            array_keys($decoded)
        );
        $this->assertSame("FR", $decoded["pre_selected_residence_country"]);
        $this->assertSame("en-US", $decoded["pre_selected_language"]);
    }

    public function testIdentityVerificationClientInformationAddsTheTwoIdvOnlyFields()
    {
        $decoded = json_decode(
            $this->serializer->serialize($this->buildIdentityVerificationClientInformation()),
            true
        );

        $this->assertSame("FR", $decoded["pre_selected_residence_country"]);
        $this->assertSame("en-US", $decoded["pre_selected_language"]);
        $this->assertSame("GB", $decoded["pre_selected_document_issuing_country"]);
        $this->assertSame("Travel Document", $decoded["pre_selected_document_type"]);
        $this->assertCount(4, $decoded);
    }

    public function testIdentityVerificationAttemptRequestSerializesPhoneNumberAndClientInformation()
    {
        $request = new IdentityVerificationAttemptRequest();
        $request->redirect_url = "https://example.com/success";
        $request->phone_number = $this->buildPhoneNumber();
        $request->client_information = $this->buildIdentityVerificationClientInformation();

        $decoded = json_decode($this->serializer->serialize($request), true);

        $this->assertSame("https://example.com/success", $decoded["redirect_url"]);
        $this->assertSame("+33", $decoded["phone_number"]["country_code"]);
        $this->assertSame("5555550102", $decoded["phone_number"]["number"]);
        $this->assertSame("GB", $decoded["client_information"]["pre_selected_document_issuing_country"]);
        $this->assertSame("Travel Document", $decoded["client_information"]["pre_selected_document_type"]);
    }

    public function testIdentityVerificationAttemptRequestFromSwaggerExample()
    {
        $json = '{'
            . '"redirect_url":"https://example.com/success",'
            . '"phone_number":{"country_code":"+33","number":"5555550102"},'
            . '"client_information":{'
            . '"pre_selected_residence_country":"FR",'
            . '"pre_selected_document_issuing_country":"GB",'
            . '"pre_selected_document_type":"Passport",'
            . '"pre_selected_language":"en-US"'
            . '}'
            . '}';

        $decoded = $this->serializer->deserialize($json);

        $this->assertSame("https://example.com/success", $decoded["redirect_url"]);
        $this->assertSame("+33", $decoded["phone_number"]["country_code"]);
        $this->assertSame("Passport", $decoded["client_information"]["pre_selected_document_type"]);
    }

    public function testFaceAuthenticationAttemptRequestSerializesPhoneNumber()
    {
        $clientInformation = new ClientInformation();
        $clientInformation->pre_selected_residence_country = "FR";

        $request = new FaceAuthenticationAttemptRequest();
        $request->redirect_url = "https://example.com/success";
        $request->phone_number = $this->buildPhoneNumber();
        $request->client_information = $clientInformation;

        $decoded = json_decode($this->serializer->serialize($request), true);

        $this->assertSame("+33", $decoded["phone_number"]["country_code"]);
        $this->assertSame("FR", $decoded["client_information"]["pre_selected_residence_country"]);
        $this->assertArrayNotHasKey("pre_selected_document_type", $decoded["client_information"]);
        $this->assertArrayNotHasKey(
            "pre_selected_document_issuing_country",
            $decoded["client_information"]
        );
    }

    public function testIdentityVerificationRequestCarriesTheIdentityDeclaredDataFields()
    {
        $request = new IdentityVerificationRequest();
        $request->applicant_id = "aplt_tkoi5db4hryu5cei5vwoabr7we";
        $request->user_journey_id = "usj_tkoi5db4hryu5cei5vwoabr7we";
        $request->declared_data = $this->buildIdentityDeclaredData();

        $decoded = json_decode($this->serializer->serialize($request), true);

        $this->assertSame("aplt_tkoi5db4hryu5cei5vwoabr7we", $decoded["applicant_id"]);
        $this->assertSame("usj_tkoi5db4hryu5cei5vwoabr7we", $decoded["user_journey_id"]);
        $this->assertSame("hannah.bret@example.com", $decoded["declared_data"]["email"]);
        $this->assertSame("+33", $decoded["declared_data"]["phone_number"]["country_code"]);
        $this->assertSame("GB", $decoded["declared_data"]["address"]["country"]);
    }

    public function testIdentityVerificationAndOpenRequestCarriesTheIdentityDeclaredDataFields()
    {
        $request = new IdentityVerificationAndOpenRequest();
        $request->applicant_id = "aplt_tkoi5db4hryu5cei5vwoabr7we";
        $request->user_journey_id = "usj_tkoi5db4hryu5cei5vwoabr7we";
        $request->redirect_url = "https://example.com/success";
        $request->declared_data = $this->buildIdentityDeclaredData();

        $decoded = json_decode($this->serializer->serialize($request), true);

        $this->assertSame("https://example.com/success", $decoded["redirect_url"]);
        $this->assertSame("1994-10-15", $decoded["declared_data"]["birth_date"]);
        $this->assertSame("London", $decoded["declared_data"]["address"]["city"]);
    }

    public function testAttemptsQueryFilterEncodesSkipAndLimit()
    {
        $query = new AttemptsQueryFilter();
        $query->skip = 5;
        $query->limit = 25;

        $this->assertSame("skip=5&limit=25", $query->getEncodedQueryParameters());
    }

    public function testAttemptsQueryFilterEncodesLimitOnly()
    {
        $query = new AttemptsQueryFilter();
        $query->limit = 25;

        $this->assertSame("limit=25", $query->getEncodedQueryParameters());
    }

    public function testEmptyAttemptsQueryFilterEncodesToAnEmptyString()
    {
        $this->assertSame("", (new AttemptsQueryFilter())->getEncodedQueryParameters());
    }

    public function testAttemptAssetsQueryFilterEncodesSkipAndLimit()
    {
        $query = new AttemptAssetsQueryFilter();
        $query->skip = 2;
        $query->limit = 50;

        $this->assertSame("skip=2&limit=50", $query->getEncodedQueryParameters());
    }

    private function buildPhoneNumber(): PhoneNumber
    {
        $phoneNumber = new PhoneNumber();
        $phoneNumber->country_code = "+33";
        $phoneNumber->number = "5555550102";

        return $phoneNumber;
    }

    private function buildIdentityDeclaredData(): IdentityDeclaredData
    {
        $address = new IdvAddress();
        $address->address_line1 = "123 Main Street";
        $address->city = "London";
        $address->zip = "SW1A 1AA";
        $address->country = "GB";

        $declaredData = new IdentityDeclaredData();
        $declaredData->name = "Hannah Bret";
        $declaredData->birth_date = "1994-10-15";
        $declaredData->email = "hannah.bret@example.com";
        $declaredData->phone_number = $this->buildPhoneNumber();
        $declaredData->address = $address;

        return $declaredData;
    }

    private function buildIdentityVerificationClientInformation(): IdentityVerificationClientInformation
    {
        $clientInformation = new IdentityVerificationClientInformation();
        $clientInformation->pre_selected_residence_country = "FR";
        $clientInformation->pre_selected_language = "en-US";
        $clientInformation->pre_selected_document_issuing_country = "GB";
        $clientInformation->pre_selected_document_type = "Travel Document";

        return $clientInformation;
    }
}
