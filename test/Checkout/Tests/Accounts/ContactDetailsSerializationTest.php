<?php

namespace Checkout\Tests\Accounts;

use Checkout\Accounts\ContactDetails;
use Checkout\Accounts\EntityEmailAddresses;
use Checkout\Accounts\Invitee;
use Checkout\Common\Phone;
use Checkout\JsonSerializer;
use PHPUnit\Framework\TestCase;

class ContactDetailsSerializationTest extends TestCase
{
    public function testContactDetailsInviteeRoundTrip()
    {
        $phone = new Phone();
        $phone->country_code = "FR";
        $phone->number = "0712345678";

        $emailAddresses = new EntityEmailAddresses();
        $emailAddresses->primary = "owner@example.com";

        $invitee = new Invitee();
        $invitee->email = "invitee@example.com";

        $contactDetails = new ContactDetails();
        $contactDetails->phone = $phone;
        $contactDetails->email_addresses = $emailAddresses;
        $contactDetails->invitee = $invitee;

        $decoded = json_decode((new JsonSerializer())->serialize($contactDetails), true);

        $this->assertSame(
            array(
                "phone" => array("country_code" => "FR", "number" => "0712345678"),
                "email_addresses" => array("primary" => "owner@example.com"),
                "invitee" => array("email" => "invitee@example.com"),
            ),
            $decoded
        );
    }

    public function testUsIsvSellerContactDetailsMatchSwaggerExample()
    {
        // contact_details from the US ISV Seller Company (3.0) request example in the spec, with a
        // neutral primary address in place of the example's.
        $swaggerExample = '{"phone":{"country_code":"US","number":"4155678900"},'
            . '"email_addresses":{"primary":"owner@example.com",'
            . '"pci_compliance_contact":"pci.contact@example.com"}}';

        $phone = new Phone();
        $phone->country_code = "US";
        $phone->number = "4155678900";

        $emailAddresses = new EntityEmailAddresses();
        $emailAddresses->primary = "owner@example.com";
        $emailAddresses->pci_compliance_contact = "pci.contact@example.com";

        $contactDetails = new ContactDetails();
        $contactDetails->phone = $phone;
        $contactDetails->email_addresses = $emailAddresses;

        $serialized = (new JsonSerializer())->serialize($contactDetails);
        $decoded = json_decode($serialized, true);

        $this->assertSame("owner@example.com", $decoded["email_addresses"]["primary"]);
        $this->assertSame("pci.contact@example.com", $decoded["email_addresses"]["pci_compliance_contact"]);
        $this->assertArrayNotHasKey("invitee", $decoded);
        $this->assertEquals(json_decode($swaggerExample, true), $decoded);
    }

    /**
     * In every v2.0 variant contact_details.phone defines number only; country_code exists only
     * in v3.0. Leaving country_code unset must drop the key, not send it as null.
     */
    public function testV2NumberOnlyPhoneOmitsCountryCode()
    {
        $phone = new Phone();
        $phone->number = "4155678900";

        $emailAddresses = new EntityEmailAddresses();
        $emailAddresses->primary = "owner@example.com";

        $contactDetails = new ContactDetails();
        $contactDetails->phone = $phone;
        $contactDetails->email_addresses = $emailAddresses;

        $decoded = json_decode((new JsonSerializer())->serialize($contactDetails), true);

        $this->assertSame(
            array(
                "phone" => array("number" => "4155678900"),
                "email_addresses" => array("primary" => "owner@example.com"),
            ),
            $decoded
        );
        $this->assertArrayNotHasKey("country_code", $decoded["phone"]);
    }
}
