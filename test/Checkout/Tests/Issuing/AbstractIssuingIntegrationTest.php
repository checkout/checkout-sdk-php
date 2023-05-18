<?php

namespace Checkout\Tests\Issuing;

use Checkout\CheckoutApi;
use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\CheckoutSdk;
use Checkout\Common\DocumentType;
use Checkout\Environment;
use Checkout\Issuing\Cardholders\CardholderDocument;
use Checkout\Issuing\Cardholders\CardholderRequest;
use Checkout\Issuing\Cardholders\CardholderType;
use Checkout\Issuing\Cards\Create\CardLifetime;
use Checkout\Issuing\Cards\Create\LifetimeUnit;
use Checkout\Issuing\Cards\Create\VirtualCardRequest;
use Checkout\Issuing\Controls\Create\VelocityCardControlRequest;
use Checkout\Issuing\Controls\VelocityLimit;
use Checkout\Issuing\Controls\VelocityWindow;
use Checkout\Issuing\Controls\VelocityWindowType;
use Checkout\OAuthScope;
use Checkout\PlatformType;
use Checkout\Tests\SandboxTestFixture;

abstract class AbstractIssuingIntegrationTest extends SandboxTestFixture
{

    /**
     * @var CheckoutApi
     */
    protected $issuingApi;

    /**
     * @before
     * @throws CheckoutAuthorizationException
     * @throws CheckoutArgumentException
     * @throws CheckoutException
     */
    public function before()
    {
        $this->init(PlatformType::$default);
        $this->issuingApi = $this->createIssuingApi();
    }

    /**
     * @throws CheckoutArgumentException
     * @throws CheckoutException
     * @return CheckoutApi
     */
    private function createIssuingApi()
    {
        return CheckoutSdk::builder()
            ->oAuth()
            ->clientCredentials(
                getenv("CHECKOUT_DEFAULT_OAUTH_ISSUING_CLIENT_ID"),
                getenv("CHECKOUT_DEFAULT_OAUTH_ISSUING_CLIENT_SECRET")
            )
            ->scopes([
                OAuthScope::$issuingClient,
                OAuthScope::$issuingCardMgmt,
                OAuthScope::$issuingControlsRead,
                OAuthScope::$issuingControlsWrite])
            ->environment(Environment::sandbox())
            ->build();
    }

    /**
     * @return mixed
     * @throws CheckoutApiException
     */
    protected function createCardholder()
    {
        $address = $this->getAddress();

        $document = new CardholderDocument();
        $document->type = DocumentType::$national_identity_card;
        $document->front_document_id = "file_6lbss42ezvoufcb2beo76rvwly";
        $document->back_document_id = "file_aaz5pemp6326zbuvevp6qroqu4";

        $cardholderRequest = new CardholderRequest();
        $cardholderRequest->type = CardholderType::$individual;
        $cardholderRequest->reference = "X-123456-N11";
        $cardholderRequest->entity_id = "ent_mujh2nia2ypezmw5fo2fofk7ka";
        $cardholderRequest->first_name = "John";
        $cardholderRequest->middle_name = "Fitzgerald";
        $cardholderRequest->last_name = "Kennedy";
        $cardholderRequest->email = "john.kennedy@myemaildomain.com";
        $cardholderRequest->phone_number = $this->getPhone();
        $cardholderRequest->date_of_birth = "1985-05-15";
        $cardholderRequest->billing_address = $address;
        $cardholderRequest->residency_address = $address;
        $cardholderRequest->document = $document;

        $cardholderResponse = $this->issuingApi->getIssuingClient()->createCardholder($cardholderRequest);

        $this->assertResponse($cardholderResponse, "id");
        return $cardholderResponse;
    }

    /**
     * @param $cardholderId string
     * @param $activate boolean
     * @return mixed
     * @throws CheckoutApiException
     */
    protected function createCard($cardholderId, $activate = false)
    {
        $lifetime = new CardLifetime();
        $lifetime->unit = LifetimeUnit::$months;
        $lifetime->value = 6;

        $cardRequest = new VirtualCardRequest();
        $cardRequest->cardholder_id = $cardholderId;
        $cardRequest->card_product_id = "pro_3fn6pv2ikshurn36dbd3iysyha";
        $cardRequest->lifetime = $lifetime;
        $cardRequest->reference = "X-123456-N11";
        $cardRequest->display_name = "John Kennedy";
        $cardRequest->is_single_use = false;
        $cardRequest->activate_card = $activate;

        $cardResponse = $this->issuingApi->getIssuingClient()->createCard($cardRequest);

        $this->assertResponse($cardResponse, "id");
        return $cardResponse;
    }

    /**
     * @param $cardId string
     * @return mixed
     * @throws CheckoutApiException
     */
    protected function createCardControl($cardId)
    {
        $windowType = new VelocityWindow();
        $windowType->type = VelocityWindowType::$weekly;

        $velocityLimit = new VelocityLimit();
        $velocityLimit->amount_limit = 500;
        $velocityLimit->velocity_window = $windowType;

        $controlRequest = new VelocityCardControlRequest();
        $controlRequest->description = "Max spend of 500€ per week for restaurants";
        $controlRequest->target_id = $cardId;
        $controlRequest->velocity_limit = $velocityLimit;

        $controlResponse = $this->issuingApi->getIssuingClient()->createControl($controlRequest);

        $this->assertResponse($controlResponse, "id");
        return $controlResponse;
    }

    /**
     * @return string
     */
    protected function getPassword()
    {
        return "Xtui43FvfiZ";
    }
}
