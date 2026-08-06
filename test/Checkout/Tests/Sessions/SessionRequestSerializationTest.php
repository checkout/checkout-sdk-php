<?php

namespace Checkout\Tests\Sessions;

use Checkout\Common\Country;
use Checkout\Common\Currency;
use Checkout\Common\Phone;
use Checkout\JsonSerializer;
use Checkout\Sessions\AuthenticationType;
use Checkout\Sessions\CardholderAccountInfo;
use Checkout\Sessions\Category;
use Checkout\Sessions\Channel\BrowserSession;
use Checkout\Sessions\Completion\NonHostedCompletionInfo;
use Checkout\Sessions\DeviceInformation;
use Checkout\Sessions\GoogleSpa;
use Checkout\Sessions\InitialTransaction;
use Checkout\Sessions\Installment;
use Checkout\Sessions\MerchantRiskInfo;
use Checkout\Sessions\Optimization;
use Checkout\Sessions\Recurring;
use Checkout\Sessions\SessionAddress;
use Checkout\Sessions\SessionChallengeIndicatorType;
use Checkout\Sessions\SessionMarketplaceData;
use Checkout\Sessions\SessionRequest;
use Checkout\Sessions\SessionsBillingDescriptor;
use Checkout\Sessions\Source\SessionCardSource;
use Checkout\Sessions\TransactionType;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * Full-property serialization coverage for the POST /sessions request body.
 *
 * Every one of the class's 24 properties is populated and asserted on the emitted JSON, with a
 * reflection guard so that adding a property without extending the fixture fails the test.
 */
class SessionRequestSerializationTest extends TestCase
{
    private static function fullyPopulated(): SessionRequest
    {
        $source = new SessionCardSource();
        $source->number = "4485040371536584";
        $source->expiry_month = 1;
        $source->expiry_year = 2030;
        $source->name = "Bruce Wayne";

        $billingAddress = new SessionAddress();
        $billingAddress->address_line1 = "Checkout.com";
        $billingAddress->city = "London";
        $billingAddress->zip = "W1T 4TJ";
        $billingAddress->country = Country::$GB;
        $source->billing_address = $billingAddress;

        $phone = new Phone();
        $phone->country_code = "44";
        $phone->number = "0204567895";
        $source->home_phone = $phone;

        $shippingAddress = new SessionAddress();
        $shippingAddress->address_line1 = "Checkout.com";
        $shippingAddress->address_line2 = "90 Tottenham Court Road";
        $shippingAddress->city = "London";
        $shippingAddress->state = "ENG";
        $shippingAddress->zip = "W1T 4TJ";
        $shippingAddress->country = Country::$GB;

        $marketplace = new SessionMarketplaceData();
        $marketplace->sub_entity_id = "ent_ocw5i74vowfg2edpy66izhts2u";

        $accountInfo = new CardholderAccountInfo();
        $accountInfo->purchase_count = 10;
        $accountInfo->add_card_attempts = 5;

        $billingDescriptor = new SessionsBillingDescriptor();
        $billingDescriptor->name = "SUPERHEROES.COM";

        $merchantRiskInfo = new MerchantRiskInfo();
        $merchantRiskInfo->delivery_email = "bruce@wayne-enterprises.com";
        $merchantRiskInfo->is_preorder = false;
        $merchantRiskInfo->is_reorder = false;

        $completion = new NonHostedCompletionInfo();
        $completion->callback_url = "https://merchant.com/callback";

        $browserSession = new BrowserSession();
        $browserSession->accept_header = "Accept:  *.*, q=0.1";
        $browserSession->java_enabled = true;
        $browserSession->language = "FR-fr";
        $browserSession->ip_address = "1.12.123.255";

        $recurring = new Recurring();
        $recurring->days_between_payments = 30;
        $recurring->expiry = "99991231";

        $installment = new Installment();
        $installment->number_of_payments = 3;
        $installment->days_between_payments = 30;
        $installment->expiry = "99991231";

        $optimization = new Optimization();
        $optimization->framework = "acceptance_rates";

        $initialTransaction = new InitialTransaction();
        $initialTransaction->acs_transaction_id = "acs-txn-id";

        $googleSpa = new GoogleSpa();
        $googleSpa->continue_url = "https://merchant.com/continue";

        $deviceInformation = new DeviceInformation();
        $deviceInformation->device_id = "device-id";
        $deviceInformation->device_session_id = "device-session";

        $request = new SessionRequest();
        $request->source = $source;
        $request->amount = 6540;
        $request->currency = Currency::$USD;
        $request->processing_channel_id = "pc_5jp2az55l3cuths25t5p3xhwru";
        $request->marketplace = $marketplace;
        $request->authentication_type = AuthenticationType::$regular;
        $request->authentication_category = Category::$payment;
        $request->account_info = $accountInfo;
        $request->challenge_indicator = SessionChallengeIndicatorType::$trusted_listing_prompt;
        $request->billing_descriptor = $billingDescriptor;
        $request->reference = "ORD-5023-4E89";
        $request->merchant_risk_info = $merchantRiskInfo;
        $request->transaction_type = TransactionType::$goods_service;
        $request->shipping_address = $shippingAddress;
        $request->shipping_address_matches_billing = true;
        $request->completion = $completion;
        $request->channel_data = $browserSession;
        $request->recurring = $recurring;
        $request->installment = $installment;
        $request->optimization = $optimization;
        $request->initial_transaction = $initialTransaction;
        $request->preferred_experiences = ["3ds", "google_spa"];
        $request->google_spa = $googleSpa;
        $request->device_information = $deviceInformation;

        return $request;
    }

    private static function serialize(SessionRequest $request): array
    {
        return json_decode((new JsonSerializer())->serialize($request), true);
    }

    public function testSerializesWithDefaultsOnly()
    {
        $decoded = self::serialize(new SessionRequest());

        $this->assertSame("regular", $decoded['authentication_type']);
        $this->assertSame("payment", $decoded['authentication_category']);
        $this->assertSame("no_preference", $decoded['challenge_indicator']);
        $this->assertSame("goods_service", $decoded['transaction_type']);
    }

    /**
     * Guards against a property being silently dropped: the fixture must populate every declared
     * property, and every one must appear in the serialized payload.
     */
    public function testSerializesEveryDeclaredProperty()
    {
        $request = self::fullyPopulated();
        $decoded = self::serialize($request);

        $properties = (new ReflectionClass(SessionRequest::class))->getProperties();
        $this->assertCount(24, $properties);

        foreach ($properties as $property) {
            $name = $property->getName();

            $this->assertNotNull(
                $property->getValue($request),
                "fixture does not populate \$$name"
            );
            $this->assertArrayHasKey(
                $name,
                $decoded,
                "property \$$name is missing from the serialized payload"
            );
        }
    }

    public function testSerializesScalarsAndValueClasses()
    {
        $decoded = self::serialize(self::fullyPopulated());

        $this->assertSame(6540, $decoded['amount']);
        $this->assertSame("USD", $decoded['currency']);
        $this->assertSame("pc_5jp2az55l3cuths25t5p3xhwru", $decoded['processing_channel_id']);
        $this->assertSame("regular", $decoded['authentication_type']);
        $this->assertSame("payment", $decoded['authentication_category']);
        $this->assertSame("trusted_listing_prompt", $decoded['challenge_indicator']);
        $this->assertSame("ORD-5023-4E89", $decoded['reference']);
        $this->assertSame("goods_service", $decoded['transaction_type']);
        $this->assertTrue($decoded['shipping_address_matches_billing']);
        $this->assertSame(["3ds", "google_spa"], $decoded['preferred_experiences']);
    }

    public function testSerializesNestedObjectContents()
    {
        $decoded = self::serialize(self::fullyPopulated());

        $this->assertSame("4485040371536584", $decoded['source']['number']);
        $this->assertSame("GB", $decoded['source']['billing_address']['country']);
        $this->assertSame("ent_ocw5i74vowfg2edpy66izhts2u", $decoded['marketplace']['sub_entity_id']);
        $this->assertSame(10, $decoded['account_info']['purchase_count']);
        $this->assertSame("SUPERHEROES.COM", $decoded['billing_descriptor']['name']);
        $this->assertSame("bruce@wayne-enterprises.com", $decoded['merchant_risk_info']['delivery_email']);
        $this->assertSame("ENG", $decoded['shipping_address']['state']);
        $this->assertSame("https://merchant.com/callback", $decoded['completion']['callback_url']);
        $this->assertSame("browser", $decoded['channel_data']['channel']);
        $this->assertSame(30, $decoded['recurring']['days_between_payments']);
        $this->assertSame(3, $decoded['installment']['number_of_payments']);
        $this->assertSame("acceptance_rates", $decoded['optimization']['framework']);
        $this->assertSame("acs-txn-id", $decoded['initial_transaction']['acs_transaction_id']);
        $this->assertSame("https://merchant.com/continue", $decoded['google_spa']['continue_url']);
        $this->assertSame("device-session", $decoded['device_information']['device_session_id']);
    }
}
