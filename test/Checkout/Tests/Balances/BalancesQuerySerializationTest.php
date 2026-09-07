<?php

namespace Checkout\Tests\Balances;

use Checkout\Balances\BalancesQuery;
use DateTime;
use PHPUnit\Framework\TestCase;

/**
 * Query-string encoding tests for BalancesQuery (GET /balances/{id}).
 *
 * The swagger declares withCurrencyAccountId and balancesAt in camelCase. PHP query filters emit
 * property names verbatim via get_object_vars(), so these tests pin the exact wire names: if
 * anyone renames the properties to snake_case, the API silently ignores the parameters and these
 * tests fail instead.
 *
 * Also covers the separator behaviour of AbstractQueryFilter, which previously emitted a trailing
 * "&" whenever the last declared property was unset.
 */
class BalancesQuerySerializationTest extends TestCase
{
    public function testEncodesQueryOnlyWithoutTrailingSeparator()
    {
        $filter = new BalancesQuery();
        $filter->query = "currency:GBP";

        $this->assertSame("query=currency:GBP", $filter->getEncodedQueryParameters());
    }

    public function testEncodesWithCurrencyAccountIdInCamelCase()
    {
        $filter = new BalancesQuery();
        $filter->withCurrencyAccountId = true;

        $encoded = $filter->getEncodedQueryParameters();

        // Exact value matters: PHP concatenation renders true as "1", which the API rejects
        // with a 400 invalid_request. It must be the string "true".
        $this->assertSame("withCurrencyAccountId=true", $encoded);
        $this->assertStringNotContainsString("with_currency_account_id", $encoded);
        $this->assertStringNotContainsString("=1", $encoded);
    }

    public function testEncodesBalancesAtInCamelCase()
    {
        $filter = new BalancesQuery();
        $filter->balancesAt = new DateTime("2026-05-06T13:59:59+00:00");

        $encoded = $filter->getEncodedQueryParameters();

        $this->assertStringContainsString("balancesAt=", $encoded);
        $this->assertStringNotContainsString("balances_at", $encoded);
        $this->assertStringNotContainsString("&", $encoded);
    }

    public function testEncodesAllParametersSeparatedByAmpersand()
    {
        $filter = new BalancesQuery();
        $filter->query = "currency:GBP";
        $filter->withCurrencyAccountId = true;
        $filter->balancesAt = new DateTime("2026-05-06T13:59:59+00:00");

        $encoded = $filter->getEncodedQueryParameters();

        $this->assertStringContainsString("query=currency:GBP", $encoded);
        $this->assertStringContainsString("withCurrencyAccountId=", $encoded);
        $this->assertStringContainsString("balancesAt=", $encoded);
        $this->assertSame(2, substr_count($encoded, "&"));
        $this->assertStringEndsNotWith("&", $encoded);
    }

    public function testEncodesNothingWhenNoParametersAreSet()
    {
        $filter = new BalancesQuery();

        $this->assertSame("", $filter->getEncodedQueryParameters());
    }
}
