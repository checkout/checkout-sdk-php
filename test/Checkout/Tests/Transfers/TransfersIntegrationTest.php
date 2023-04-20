<?php

namespace Checkout\Tests\Transfers;

use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\PlatformType;
use Checkout\Tests\SandboxTestFixture;
use Checkout\Transfers\CreateTransferRequest;
use Checkout\Transfers\TransferDestination;
use Checkout\Transfers\TransferSource;
use Checkout\Transfers\TransferType;

class TransfersIntegrationTest extends SandboxTestFixture
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
    public function shouldInitiateTransferOfFundsIdempotently()
    {
        $transferSource = new TransferSource();
        $transferSource->id = "ent_kidtcgc3ge5unf4a5i6enhnr5m";
        $transferSource->amount = 100;

        $transferDestination = new TransferDestination();
        $transferDestination->id = "ent_w4jelhppmfiufdnatam37wrfc4";

        $transferRequest = new CreateTransferRequest();
        $transferRequest->transfer_type = TransferType::$commission;
        $transferRequest->source = $transferSource;
        $transferRequest->destination = $transferDestination;

        $idempotencyKey = self::idempotencyKey();

        $response1 = $this->checkoutApi->getTransfersClient()->initiateTransferOfFunds(
            $transferRequest,
            $idempotencyKey
        );

        $this->assertResponse($response1, "id", "status");

        try {
            $this->checkoutApi->getTransfersClient()->initiateTransferOfFunds(
                $transferRequest,
                $idempotencyKey
            );
            $this->fail("shouldn't get here!");
        } catch (CheckoutApiException $e) {
            $this->assertEquals(self::MESSAGE_409, $e->getMessage());
            $this->assertNotEmpty($e->http_metadata->getHeaders()["Cko-Request-Id"]);
        }
    }
}
