<?php

namespace Checkout\Tests\Payments\Links;

use Checkout\CheckoutApiException;
use Checkout\Payments\Links\PaymentLinkRequest;
use Checkout\Payments\Links\PaymentLinksClient;
use Checkout\PlatformType;
use Checkout\Tests\UnitTestFixture;


class PaymentLinksClientTest extends UnitTestFixture
{
    private PaymentLinksClient $client;

    /**
     * @before
     */
    public function init(): void
    {
        $this->initMocks(PlatformType::$default);
        $this->client = new PaymentLinksClient($this->apiClient, $this->configuration);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetPaymentLink(): void
    {

        $this->apiClient
            ->method("get")
            ->willReturn("response");

        $response = $this->client->getPaymentLink("id");
        $this->assertNotNull($response);
    }


    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreatePaymentLink(): void
    {

        $this->apiClient
            ->method("post")
            ->willReturn("response");

        $response = $this->client->createPaymentLink(new PaymentLinkRequest());
        $this->assertNotNull($response);
    }

}
