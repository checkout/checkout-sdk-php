<?php

namespace Checkout\Tests\Payments\Setups;

use Checkout\CheckoutApiException;
use Checkout\Payments\Setups\PaymentSetupsClient;
use Checkout\Payments\Setups\Request\PaymentSetupRequest;
use Checkout\PlatformType;
use Checkout\Tests\UnitTestFixture;

class PaymentSetupsClientTest extends UnitTestFixture
{
    /**
     * @var PaymentSetupsClient
     */
    private $client;

    /**
     * @before
     */
    public function init()
    {
        $this->initMocks(PlatformType::$default);
        $this->client = new PaymentSetupsClient($this->apiClient, $this->configuration);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreatePaymentSetup()
    {
        $this->apiClient
            ->method("post")
            ->willReturn(["response"]);

        $response = $this->client->createPaymentSetup(new PaymentSetupRequest());
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldUpdatePaymentSetup()
    {
        $paymentSetupId = "setup_123456";

        $this->apiClient
            ->method("put")
            ->with(
                $this->equalTo("payments/setups/" . $paymentSetupId),
                $this->isInstanceOf(PaymentSetupRequest::class),
                $this->anything()
            )
            ->willReturn(["response"]);

        $response = $this->client->updatePaymentSetup($paymentSetupId, new PaymentSetupRequest());
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetPaymentSetup()
    {
        $paymentSetupId = "setup_123456";

        $this->apiClient
            ->method("get")
            ->with(
                $this->equalTo("payments/setups/" . $paymentSetupId),
                $this->anything()
            )
            ->willReturn(["response"]);

        $response = $this->client->getPaymentSetup($paymentSetupId);
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldConfirmPaymentSetup()
    {
        $paymentSetupId = "setup_123456";
        $paymentMethodOptionId = "opt_123456";

        $this->apiClient
            ->method("post")
            ->with(
                $this->equalTo("payments/setups/" . $paymentSetupId . "/confirm/" . $paymentMethodOptionId),
                $this->isNull(),
                $this->anything(),
                $this->isNull()
            )
            ->willReturn(["response"]);

        $response = $this->client->confirmPaymentSetup($paymentSetupId, $paymentMethodOptionId);
        $this->assertNotNull($response);
    }
}
