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
}
