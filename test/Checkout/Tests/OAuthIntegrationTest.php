<?php

namespace Checkout\Tests;

use Checkout\CheckoutFourSdk;
use Checkout\Common\Address;
use Checkout\Common\Country;
use Checkout\Common\Currency;
use Checkout\Common\MarketplaceData;
use Checkout\Environment;
use Checkout\Payments\Four\Request\PaymentRequest;
use Checkout\Payments\Four\Request\Source\RequestCardSource;
use Checkout\Payments\Four\Sender\PaymentIndividualSender;
use Checkout\PlatformType;
use Exception;

class OAuthIntegrationTest extends SandboxTestFixture
{

    /**
     * @before
     */
    public function before(): void
    {
        $this->init(PlatformType::$fourOAuth);
    }

    /**
     * @test
     */
    public function shouldMakePaymentWithOAuth(): void
    {

        $requestCardSource = new RequestCardSource();
        $requestCardSource->name = TestCardSource::$VisaName;
        $requestCardSource->number = TestCardSource::$VisaNumber;
        $requestCardSource->expiry_year = TestCardSource::$VisaExpiryYear;
        $requestCardSource->expiry_month = TestCardSource::$VisaExpiryMonth;
        $requestCardSource->cvv = TestCardSource::$VisaCvv;

        $address = new Address();
        $address->address_line1 = "CheckoutSdk.com";
        $address->address_line2 = "90 Tottenham Court Road";
        $address->city = "London";
        $address->state = "London";
        $address->zip = "W1T 4TJ";
        $address->country = Country::$GB;

        $paymentIndividualSender = new PaymentIndividualSender();
        $paymentIndividualSender->fist_name = "Mr";
        $paymentIndividualSender->last_name = "Test";
        $paymentIndividualSender->address = $address;

        $marketplace = new MarketplaceData();
        $marketplace->sub_entity_id = "ent_ocw5i74vowfg2edpy66izhts2u";

        $paymentRequest = new PaymentRequest();
        $paymentRequest->source = $requestCardSource;
        $paymentRequest->capture = false;
        $paymentRequest->reference = uniqid();
        $paymentRequest->amount = 10;
        $paymentRequest->currency = Currency::$EUR;
        $paymentRequest->processing_channel_id = "pc_a6dabcfa2o3ejghb3sjuotdzzy";
        $paymentRequest->marketplace = $marketplace;
        $paymentRequest->sender = $paymentIndividualSender;

        $paymentResponse = $this->fourApi->getPaymentsClient()->requestPayment($paymentRequest);

        $this->assertResponse($paymentResponse, "id");

    }

    /**
     * @test
     */
    public function shouldFailInitAuthorization_InvalidCredentials(): void
    {

        try {
            $builder = CheckoutFourSdk::oAuth();
            $builder->clientCredentials("fake", "fake");
            $builder->setEnvironment(Environment::sandbox());
            $builder->setFilesEnvironment(Environment::sandbox());
            $this->fourApi = $builder->build();
            self::fail("shouldn't get here");
        } catch (Exception $e) {
            $this->assertEquals("Client error: `POST https://access.sandbox.checkout.com/connect/token` resulted in a `400 Bad Request` response:\n{\"error\":\"invalid_client\"}\n", $e->getMessage());
        }

    }

    /**
     * @test
     */
    public function shouldFailInitAuthorization_CustomFakeAuthorizationUri(): void
    {
        try {
            $builder = CheckoutFourSdk::oAuth();
            $builder->clientCredentials("fake", "fake");
            $builder->authorizationUri("https://test.checkout.com");
            $builder->setEnvironment(Environment::sandbox());
            $builder->setFilesEnvironment(Environment::sandbox());
            $this->fourApi = $builder->build();
            self::fail("shouldn't get here");
        } catch (Exception $e) {
            $this->assertEquals("cURL error 6: Could not resolve host: test.checkout.com (see https://curl.haxx.se/libcurl/c/libcurl-errors.html) for https://test.checkout.com", $e->getMessage());
        }

    }

}
