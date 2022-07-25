<?php

namespace Checkout\Tests\Accounts;

use Checkout\Accounts\DaySchedule;
use Checkout\Accounts\ScheduleFrequencyDailyRequest;
use Checkout\Accounts\ScheduleFrequencyMonthlyRequest;
use Checkout\Accounts\ScheduleFrequencyWeeklyRequest;
use Checkout\Accounts\UpdateScheduleRequest;
use Checkout\CheckoutApi;
use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\CheckoutSdk;
use Checkout\Common\Currency;
use Checkout\OAuthScope;
use Checkout\PlatformType;
use Checkout\Tests\SandboxTestFixture;

class AccountsPayoutSchedulesIntegrationTest extends SandboxTestFixture
{
    /**
     * @before
     * @throws
     */
    public function before()
    {
        try {
            $this->init(PlatformType::$default_oauth);
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
        $weeklyRequest = new ScheduleFrequencyWeeklyRequest();
        $weeklyRequest->by_day = [DaySchedule::$FRIDAY, DaySchedule::$MONDAY];

        $scheduleRequest = new UpdateScheduleRequest();
        $scheduleRequest->enabled = true;
        $scheduleRequest->threshold = 1000;
        $scheduleRequest->recurrence = $weeklyRequest;

        self::getPayoutSchedulesCheckoutApi()->getAccountsClient()->updatePayoutSchedule(
            "ent_sdioy6bajpzxyl3utftdp7legq",
            Currency::$USD,
            $scheduleRequest
        );


        $payoutSchedule = self::getPayoutSchedulesCheckoutApi()->getAccountsClient()
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
        $dailyRequest = new ScheduleFrequencyDailyRequest();

        $scheduleRequest = new UpdateScheduleRequest();
        $scheduleRequest->enabled = true;
        $scheduleRequest->threshold = 1000;
        $scheduleRequest->recurrence = $dailyRequest;

        self::getPayoutSchedulesCheckoutApi()->getAccountsClient()->updatePayoutSchedule(
            "ent_sdioy6bajpzxyl3utftdp7legq",
            Currency::$USD,
            $scheduleRequest
        );


        $payoutSchedule = self::getPayoutSchedulesCheckoutApi()->getAccountsClient()
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
        $monthlyRequest = new ScheduleFrequencyMonthlyRequest();
        $monthlyRequest->by_month_day = [10, 5];

        $scheduleRequest = new UpdateScheduleRequest();
        $scheduleRequest->enabled = true;
        $scheduleRequest->threshold = 1000;
        $scheduleRequest->recurrence = $monthlyRequest;

        self::getPayoutSchedulesCheckoutApi()->getAccountsClient()->updatePayoutSchedule(
            "ent_sdioy6bajpzxyl3utftdp7legq",
            Currency::$USD,
            $scheduleRequest
        );


        $payoutSchedule = self::getPayoutSchedulesCheckoutApi()->getAccountsClient()
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
        return CheckoutSdk::builder()->oAuth()
            ->clientCredentials(
                getenv("CHECKOUT_DEFAULT_OAUTH_PAYOUT_SCHEDULE_CLIENT_ID"),
                getenv("CHECKOUT_DEFAULT_OAUTH_PAYOUT_SCHEDULE_CLIENT_SECRET")
            )
            ->scopes([OAuthScope::$Marketplace])
            ->build();
    }
}
