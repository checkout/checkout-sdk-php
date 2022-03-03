<?php

namespace Checkout\Tests\Sessions;

use Checkout\CheckoutApiException;
use Checkout\CheckoutAuthorizationException;
use Checkout\Common\ChallengeIndicatorType;
use Checkout\Common\Country;
use Checkout\Common\Currency;
use Checkout\Common\Phone;
use Checkout\PlatformType;
use Checkout\Sessions\AuthenticationType;
use Checkout\Sessions\Category;
use Checkout\Sessions\Channel\AppSession;
use Checkout\Sessions\Channel\BrowserSession;
use Checkout\Sessions\Channel\ChannelData;
use Checkout\Sessions\Channel\SdkEphemeralPublicKey;
use Checkout\Sessions\Channel\SdkInterfaceType;
use Checkout\Sessions\Channel\ThreeDsMethodCompletion;
use Checkout\Sessions\Completion\HostedCompletionInfo;
use Checkout\Sessions\Completion\NonHostedCompletionInfo;
use Checkout\Sessions\SessionAddress;
use Checkout\Sessions\SessionMarketplaceData;
use Checkout\Sessions\SessionRequest;
use Checkout\Sessions\SessionsBillingDescriptor;
use Checkout\Sessions\Source\SessionCardSource;
use Checkout\Sessions\TransactionType;
use Checkout\Sessions\UIElements;
use Checkout\Tests\SandboxTestFixture;
use Checkout\Tests\TestCardSource;

abstract class AbstractSessionsIntegrationTest extends SandboxTestFixture
{

    /**
     * @before
     * @throws CheckoutAuthorizationException
     */
    public function before(): void
    {
        $this->init(PlatformType::$fourOAuth);
    }

    /**
     * @param ChannelData $channelData
     * @param string $authenticationCategory
     * @param string $challengeIndicatorType
     * @param string $transactionType
     * @return mixed
     * @throws CheckoutApiException
     */
    protected function createNonHostedSession(channelData $channelData, string $authenticationCategory,
                                              string      $challengeIndicatorType, string $transactionType)
    {

        $billingAddress = new SessionAddress();
        $billingAddress->address_line1 = "CheckoutSdk.com";
        $billingAddress->address_line2 = "90 Tottenham Court Road";
        $billingAddress->city = "London";
        $billingAddress->state = "ENG";
        $billingAddress->country = Country::$GB;

        $sessionCardSource = new SessionCardSource();
        $sessionCardSource->billing_address = $billingAddress;
        $sessionCardSource->number = TestCardSource::$VisaNumber;
        $sessionCardSource->expiry_month = TestCardSource::$VisaExpiryMonth;
        $sessionCardSource->expiry_year = TestCardSource::$VisaExpiryYear;
        $sessionCardSource->name = "John Doe";
        $sessionCardSource->email = $this->randomEmail();
        $sessionCardSource->home_phone = $this->getPhone();
        $sessionCardSource->work_phone = $this->getPhone();
        $sessionCardSource->mobile_phone = $this->getPhone();

        $shippingAddress = new SessionAddress();
        $shippingAddress->address_line1 = "CheckoutSdk.com";
        $shippingAddress->address_line2 = "ABC building";
        $shippingAddress->address_line3 = "14 Wells Mews";
        $shippingAddress->city = "London";
        $shippingAddress->state = "ENG";
        $shippingAddress->zip = "W1T 4TJ";
        $shippingAddress->country = Country::$GB;

        $marketPlaceData = new SessionMarketplaceData();
        $marketPlaceData->sub_entity_id = "ent_ocw5i74vowfg2edpy66izhts2u";

        $billingDescriptor = new SessionsBillingDescriptor();
        $billingDescriptor->name = "SUPERHEROES.COM";

        $nonHostedCompletionInfo = new NonHostedCompletionInfo();
        $nonHostedCompletionInfo->callback_url = "https://merchant.com/callback";

        $sessionRequest = new SessionRequest();
        $sessionRequest->source = $sessionCardSource;
        $sessionRequest->amount = 6540;
        $sessionRequest->currency = Currency::$USD;
        $sessionRequest->processing_channel_id = "pc_5jp2az55l3cuths25t5p3xhwru";
        $sessionRequest->marketplace = $marketPlaceData;
        $sessionRequest->authentication_category = $authenticationCategory;
        $sessionRequest->challenge_indicator = $challengeIndicatorType;
        $sessionRequest->billing_descriptor = $billingDescriptor;
        $sessionRequest->reference = "ORD-5023-4E89";
        $sessionRequest->transaction_type = $transactionType;
        $sessionRequest->shipping_address = $shippingAddress;
        $sessionRequest->completion = $nonHostedCompletionInfo;
        $sessionRequest->channel_data = $channelData;

        $responseNonHostedSession = $this->fourApi->getSessionsClient()->requestSession($sessionRequest);

        $this->assertResponse($responseNonHostedSession, "id", "session_secret");

        return $responseNonHostedSession;
    }

    protected function getPhone(): Phone
    {
        $phone = new Phone();
        $phone->number = "0204567895";
        $phone->country_code = "234";

        return $phone;
    }

    /**
     * @return mixed
     * @throws CheckoutApiException
     */
    protected function createHostedSession()
    {
        $shippingAddress = new SessionAddress();
        $shippingAddress->address_line1 = "CheckoutSdk.com";
        $shippingAddress->address_line2 = "90 Tottenham Court Road";
        $shippingAddress->city = "London";
        $shippingAddress->state = "ENG";
        $shippingAddress->zip = "W1T 4TJ";
        $shippingAddress->country = Country::$GB;

        $sessionCardSource = new SessionCardSource();
        $sessionCardSource->number = "4485040371536584";
        $sessionCardSource->expiry_month = 1;
        $sessionCardSource->expiry_year = 2030;

        $hostedCompletionInfo = new HostedCompletionInfo();
        $hostedCompletionInfo->failure_url = "https://example.com/sessions/fail";
        $hostedCompletionInfo->success_url = "https://example.com/sessions/success";

        $sessionRequest = new SessionRequest();
        $sessionRequest->source = $sessionCardSource;
        $sessionRequest->amount = 100;
        $sessionRequest->currency = Currency::$USD;
        $sessionRequest->processing_channel_id = "pc_5jp2az55l3cuths25t5p3xhwru";
        $sessionRequest->authentication_type = AuthenticationType::$regular;
        $sessionRequest->authentication_category = Category::$payment;
        $sessionRequest->challenge_indicator = ChallengeIndicatorType::$no_preference;
        $sessionRequest->reference = "ORD-5023-4E89";
        $sessionRequest->transaction_type = TransactionType::$goods_service;
        $sessionRequest->shipping_address = $shippingAddress;
        $sessionRequest->completion = $hostedCompletionInfo;

        $responseHostedSession = $this->fourApi->getSessionsClient()->requestSession($sessionRequest);

        $this->assertResponse($responseHostedSession, "id", "session_secret");
        return $responseHostedSession;
    }

    protected function getBrowserSession(): ChannelData
    {
        $browserSession = new BrowserSession();
        $browserSession->accept_header = "Accept:  *.*, q=0.1";
        $browserSession->java_enabled = true;
        $browserSession->language = "FR-fr";
        $browserSession->color_depth = "16";
        $browserSession->screen_width = "1920";
        $browserSession->screen_height = "1080";
        $browserSession->timezone = "60";
        $browserSession->user_agent = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/69.0.3497.100 Safari/537.36";
        $browserSession->three_ds_method_completion = ThreeDsMethodCompletion::$y;
        $browserSession->ip_address = "1.12.123.255";

        return $browserSession;
    }

    protected function getAppSession(): ChannelData
    {
        $sdkEphemeralPublicKey = new SdkEphemeralPublicKey();
        $sdkEphemeralPublicKey->kty = "EC";
        $sdkEphemeralPublicKey->crv = "P-256";
        $sdkEphemeralPublicKey->x = "f83OJ3D2xF1Bg8vub9tLe1gHMzV76e8Tus9uPHvRVEU";
        $sdkEphemeralPublicKey->y = "x_FEzRu9m36HLN_tue659LNpXW6pCyStikYjKIWI5a0";

        $appSession = new AppSession();
        $appSession->sdk_app_id = "dbd64fcb-c19a-4728-8849-e3d50bfdde39";
        $appSession->sdk_max_timeout = 5;
        $appSession->sdk_encrypted_data = "{}";
        $appSession->sdk_ephem_pub_key = $sdkEphemeralPublicKey;
        $appSession->sdk_reference_number = "3DS_LOA_SDK_PPFU_020100_00007";
        $appSession->sdk_transaction_id = "b2385523-a66c-4907-ac3c-91848e8c0067";
        $appSession->sdk_interface_type = SdkInterfaceType::$both;
        $appSession->sdk_ui_elements = array(UIElements::$single_select, UIElements::$html_other);

        return $appSession;
    }
}
