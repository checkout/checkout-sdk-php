<?php

namespace Checkout\Tests\Marketplace;

use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\CheckoutFourSdk;
use Checkout\Common\Currency;
use Checkout\Four\CheckoutApi;
use Checkout\Four\FourOAuthScope;
use Checkout\Marketplace\DaySchedule;
use Checkout\Marketplace\ScheduleFrequencyDailyRequest;
use Checkout\Marketplace\ScheduleFrequencyMonthlyRequest;
use Checkout\Marketplace\ScheduleFrequencyWeeklyRequest;
use Checkout\Marketplace\UpdateScheduleRequest;
use Checkout\PlatformType;
use Checkout\Tests\SandboxTestFixture;

class MarketplacePayoutSchedulesIntegrationTest extends SandboxTestFixture
{
    /**
     * @before
     */
    public function before()
    {
        try {
            $this->init(PlatformType::$fourOAuth);
        } catch (CheckoutAuthorizationException $e) {
        }
    }

    /**
     * @test
     * @throws CheckoutApiException
     * @throws CheckoutArgumentException
     * @throws CheckoutException
     */
    public function shouldUpdateAndRetrieveWeeklyPayoutSchedules()
    {
        $this->markTestSkipped("unavailable");
        $weeklyRequest = new ScheduleFrequencyWeeklyRequest();
        $weeklyRequest->by_day = [DaySchedule::$FRIDAY, DaySchedule::$MONDAY];

        $scheduleRequest = new UpdateScheduleRequest();
        $scheduleRequest->enabled = true;
        $scheduleRequest->threshold = 1000;
        $scheduleRequest->recurrence = $weeklyRequest;

        self::getPayoutSchedulesCheckoutApi()->getMarketplaceClient()->updatePayoutSchedule(
            "ent_sdioy6bajpzxyl3utftdp7legq",
            Currency::$USD,
            $scheduleRequest
        );


        $payoutSchedule = self::getPayoutSchedulesCheckoutApi()->getMarketplaceClient()
            ->retrievePayoutSchedule("ent_sdioy6bajpzxyl3utftdp7legq");

        $this->assertResponse(
            $payoutSchedule,
            "USD",
            "USD.enabled",
            "USD.threshold",
            "USD.recurrence",
            "USD.recurrence.frequency",
            "USD.recurrence.by_day"
        );
        self::assertTrue(is_array($payoutSchedule["USD"]["recurrence"]["by_day"]));
    }

    /**
     * @test
     * @throws CheckoutApiException
     * @throws CheckoutArgumentException
     * @throws CheckoutException
     */
    public function shouldUpdateAndRetrieveDailyPayoutSchedules()
    {
        $this->markTestSkipped("unavailable");
        $dailyRequest = new ScheduleFrequencyDailyRequest();

        $scheduleRequest = new UpdateScheduleRequest();
        $scheduleRequest->enabled = true;
        $scheduleRequest->threshold = 1000;
        $scheduleRequest->recurrence = $dailyRequest;

        self::getPayoutSchedulesCheckoutApi()->getMarketplaceClient()->updatePayoutSchedule(
            "ent_sdioy6bajpzxyl3utftdp7legq",
            Currency::$USD,
            $scheduleRequest
        );


        $payoutSchedule = self::getPayoutSchedulesCheckoutApi()->getMarketplaceClient()
            ->retrievePayoutSchedule("ent_sdioy6bajpzxyl3utftdp7legq");

        $this->assertResponse(
            $payoutSchedule,
            "USD",
            "USD.enabled",
            "USD.threshold",
            "USD.recurrence",
            "USD.recurrence.frequency"
        );
    }

    /**
     * @test
     * @throws CheckoutApiException
     * @throws CheckoutArgumentException
     * @throws CheckoutException
     */
    public function shouldUpdateAndRetrieveMonthlyPayoutSchedules()
    {
        $this->markTestSkipped("unavailable");
        $monthlyRequest = new ScheduleFrequencyMonthlyRequest();
        $monthlyRequest->by_month_day = [10, 5];

        $scheduleRequest = new UpdateScheduleRequest();
        $scheduleRequest->enabled = true;
        $scheduleRequest->threshold = 1000;
        $scheduleRequest->recurrence = $monthlyRequest;

        self::getPayoutSchedulesCheckoutApi()->getMarketplaceClient()->updatePayoutSchedule(
            "ent_sdioy6bajpzxyl3utftdp7legq",
            Currency::$USD,
            $scheduleRequest
        );


        $payoutSchedule = self::getPayoutSchedulesCheckoutApi()->getMarketplaceClient()
            ->retrievePayoutSchedule("ent_sdioy6bajpzxyl3utftdp7legq");

        $this->assertResponse(
            $payoutSchedule,
            "USD",
            "USD.enabled",
            "USD.threshold",
            "USD.recurrence",
            "USD.recurrence.frequency",
            "USD.recurrence.by_month_day"
        );
        self::assertTrue(is_array($payoutSchedule["USD"]["recurrence"]["by_month_day"]));
    }

    /**
     * @return CheckoutApi
     * @throws CheckoutArgumentException
     * @throws CheckoutException
     */
    private static function getPayoutSchedulesCheckoutApi()
    {
        $builder = CheckoutFourSdk::oAuth();
        $builder->clientCredentials(
            getenv("CHECKOUT_FOUR_OAUTH_PAYOUT_SCHEDULE_CLIENT_ID"),
            getenv("CHECKOUT_FOUR_OAUTH_PAYOUT_SCHEDULE_CLIENT_SECRET")
        );
        $builder->scopes([FourOAuthScope::$Marketplace]);
        return $builder->build();
    }
}
