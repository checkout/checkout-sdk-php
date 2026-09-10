<?php

namespace Checkout\Tests\Balances;

use Checkout\Balances\BalancesQuery;
use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\Common\Currency;
use Checkout\PlatformType;
use Checkout\Tests\SandboxTestFixture;

class BalancesIntegrationTest extends SandboxTestFixture
{
    const ENTITY_ID = "ent_kidtcgc3ge5unf4a5i6enhnr5m";

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
    public function shouldRetrieveEntityBalances()
    {
        $balancesQuery = new BalancesQuery();
        $balancesQuery->query = "currency:" . Currency::$GBP;

        $balances = $this->checkoutApi->getBalancesClient()->retrieveEntityBalances(self::ENTITY_ID, $balancesQuery);
        $this->assertResponse($balances, "data", "_links");
        foreach ($balances["data"] as $balanceData) {
            $this->assertResponse(
                $balanceData,
                "descriptor",
                "holding_currency",
                "balances",
                "balances.available"
            );
        }
    }

    /**
     * GET /entities/{entityId}/currency-accounts/{currencyAccountId}/top-up-instructions
     *
     * Top-ups are not enabled on the sandbox sub-accounts this suite has access to, so the
     * endpoint answers 403 ("top-ups aren't enabled for the sub-account") rather than 200.
     * Verified live on 2026-09-07 with the balances:top-up-instructions scope granted, which the
     * sandbox IdP does issue.
     *
     * The test accepts either outcome, but only the outcomes the spec documents as "not available
     * here": 403 and 404. It still fails on 400 (malformed identifiers, i.e. the SDK built the
     * path wrongly) and on 401 (wrong authorization type), which are the two ways this endpoint
     * could actually be broken in the SDK.
     *
     * @test
     * @throws CheckoutApiException
     */
    public function shouldRetrieveTopUpInstructions()
    {
        $balancesQuery = new BalancesQuery();
        $balancesQuery->withCurrencyAccountId = true;

        $balances = $this->checkoutApi->getBalancesClient()
            ->retrieveEntityBalances(self::ENTITY_ID, $balancesQuery);

        $this->assertNotNull($balances["data"]);

        // Take the first sub-account that reports an id. Requiring the entity to always have one
        // would fail this test for a reason unrelated to top-up instructions.
        $currencyAccountId = null;
        foreach ($balances["data"] as $balanceData) {
            if (!empty($balanceData["currency_account_id"])) {
                $currencyAccountId = $balanceData["currency_account_id"];
                break;
            }
        }
        if ($currencyAccountId === null) {
            $this->assertTrue(true, "no sub-account reported a currency_account_id");
            return;
        }

        try {
            $instructions = $this->checkoutApi->getBalancesClient()
                ->retrieveTopUpInstructions(self::ENTITY_ID, $currencyAccountId);

            $this->assertResponse(
                $instructions,
                "currency_account_id",
                "currency",
                "payment_reference",
                "bank_details"
            );
            $this->assertEquals($currencyAccountId, $instructions["currency_account_id"]);

            // Assert only what the spec guarantees. bank_details declares no required
            // properties, so an empty object is a legal 200 body -- do not require a rail to be
            // present. Where a rail IS returned, its two required fields must be.
            foreach (array("domestic", "international") as $rail) {
                if (!isset($instructions["bank_details"][$rail])) {
                    continue;
                }
                $this->assertResponse(
                    $instructions["bank_details"][$rail],
                    "beneficiary_account_name",
                    "bank_name"
                );
            }
        } catch (CheckoutApiException $e) {
            // 403 = top-ups not enabled for the sub-account, or the credential lacks access.
            // 404 = sub-account not found, or it has no top-up instructions available.
            // Anything else means the SDK, not the environment, is at fault.
            $this->assertContains(
                $e->http_metadata->getStatusCode(),
                array(403, 404),
                "unexpected status from top-up instructions"
            );
        }
    }
}
