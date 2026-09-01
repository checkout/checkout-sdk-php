<?php

namespace Checkout\Tests\Apm\Bacs;

use Checkout\Apm\Bacs\BacsNotificationRequest;
use Checkout\Apm\Bacs\BacsNotificationType;
use Checkout\CheckoutApiException;
use Checkout\Common\Currency;
use Checkout\Tests\SandboxTestFixture;
use Checkout\PlatformType;

class BacsIntegrationTest extends SandboxTestFixture
{
    /**
     * @before
     * @throws \Checkout\CheckoutAuthorizationException
     * @throws \Checkout\CheckoutArgumentException
     */
    public function before()
    {
        $this->init(PlatformType::$default);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldSendBacsNotification()
    {
        $this->markTestSkipped(
            "Requires a merchant enabled for Bacs Direct Debit and an existing Bacs instrument"
        );

        $request = new BacsNotificationRequest();
        $request->source_id = "src_wmlfc3zyhqzehihu7giusaaawu";
        $request->notification_type = BacsNotificationType::$advance_notice;
        $request->collection_date = "2026-07-15";
        $request->amount = 4999;
        $request->currency = Currency::$GBP;
        $request->customer_email = "customer@example.com";
        $request->billing_descriptor = "CHECKOUT";
        $request->support_email = "support@test.com";

        $response = $this->checkoutApi->getBacsClient()->sendNotification($request);

        $this->assertResponse($response, "event_id");
    }
}
