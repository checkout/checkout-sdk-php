<?php

namespace Checkout\Tests\Forward;

use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\Forward\Requests\DestinationRequest;
use Checkout\Forward\Requests\ForwardRequest;
use Checkout\Forward\Requests\Headers;
use Checkout\Forward\Requests\MethodType;
use Checkout\Forward\Requests\NetworkToken;
use Checkout\Forward\Requests\Signatures\DlocalParameters;
use Checkout\Forward\Requests\Signatures\DlocalSignature;
use Checkout\Forward\Requests\Sources\IdSource;
use Checkout\PlatformType;
use Checkout\Tests\SandboxTestFixture;

class ForwardIntegrationTest extends SandboxTestFixture
{

    /**
     * @before
     * @throws CheckoutAuthorizationException
     * @throws CheckoutArgumentException
     * @throws CheckoutException
     */
    public function before()
    {
        $this->init(PlatformType::$default_oauth);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldForwardAnApiRequest()
    {
        $this->markTestSkipped("This test requires a valid id or token source");

        $forwardRequest = $this->createForwardRequest();

        $forwardResponse = $this->checkoutApi->getForwardClient()->forwardAnApiRequest($forwardRequest);

        $this->assertResponse(
            $forwardResponse,
            "request_id",
            "destination_response",
            "created_on",
            "reference",
            "processing_channel_id",
            "source",
            "destination_request"
        );

        $this->assertEquals($forwardRequest->reference, $forwardResponse["reference"]);
        $this->assertEquals($forwardRequest->processing_channel_id, $forwardResponse["processing_channel_id"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetForwardRequest()
    {
        $this->markTestSkipped("This test requires a valid id or token source");

        $forwardRequest = $this->createForwardRequest();

        $forwardResponse = $this->checkoutApi->getForwardClient()->forwardAnApiRequest($forwardRequest);

        $getForwardResponse = $this->checkoutApi->getForwardClient()->getForwardRequest($forwardResponse["request_id"]);

        $this->assertResponse(
            $getForwardResponse,
            "request_id",
            "entity_id",
            "created_on",
            "reference",
            "destination_request",
            "destination_response"
        );

        $this->assertEquals($forwardResponse["request_id"], $getForwardResponse["request_id"]);
        $this->assertEquals($forwardRequest->reference, $getForwardResponse["reference"]);
    }

    private function createForwardRequest()
    {
        $source = new IdSource();
        $source->id = "src_v5rgkf3gdtpuzjqesyxmyodnya";

        $headers = new Headers();
        $headers->raw = array(
            "Idempotency-Key" => "xe4fad12367dfgrds",
            "Content-Type" => "application/json"
        );
        $headers->encrypted = "<JWE encrypted JSON object with string values>";

        $dlocalParameters = new DlocalParameters();
        $dlocalParameters->secret_key = "9f439fe1a9f96e67b047d3c1a28c33a2e";

        $signature = new DlocalSignature();
        $signature->dlocal_parameters = $dlocalParameters;


        $destinationRequest = new DestinationRequest();
        $destinationRequest->url = "https://example.com/payments";
        $destinationRequest->method = MethodType::$post;
        $destinationRequest->headers = $headers;
        $destinationRequest->body = json_encode(array(
            "amount" => 1000,
            "currency" => "USD",
            "reference" => "some_reference",
            "source" => array(
                "type" => "card",
                "number" => "{{card_number}}",
                "expiry_month" => "{{card_expiry_month}}",
                "expiry_year" => "{{card_expiry_year_yyyy}}",
                "name" => "Ali Farid"
            ),
            "payment_type" => "Regular",
            "authorization_type" => "Final",
            "capture" => true,
            "processing_channel_id" => "pc_xxxxxxxxxxx",
            "risk" => array("enabled" => false),
            "merchant_initiated" => true
        ));
        $destinationRequest->signature = $signature;

        $networkToken = new NetworkToken();
        $networkToken->enabled = true;
        $networkToken->request_cryptogram = false;

        $forwardRequest = new ForwardRequest();
        $forwardRequest->source = $source;
        $forwardRequest->reference = "ORD-5023-4E89";
        $forwardRequest->processing_channel_id = "pc_azsiyswl7bwe2ynjzujy7lcjca";
        $forwardRequest->network_token = $networkToken;
        $forwardRequest->destination_request = $destinationRequest;

        return $forwardRequest;
    }
}
