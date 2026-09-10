<?php

namespace Checkout\Balances;

use Checkout\Common\AbstractQueryFilter;

/**
 * Query filter for GET /balances/{id}.
 *
 * Note on property naming: AbstractQueryFilter::getEncodedQueryParameters() uses
 * get_object_vars(), so each property name is emitted verbatim as the query-string key. The
 * swagger declares these two parameters in camelCase (withCurrencyAccountId, balancesAt), so the
 * properties must be camelCase too. Renaming them to snake_case would silently send parameters
 * the API ignores.
 */
class BalancesQuery extends AbstractQueryFilter
{
    /**
     * A query to filter the balances, for example "currency:GBP".
     * [Optional]
     * @var string
     */
    public $query;

    /**
     * Specifies if the response should include the sub-account ID that corresponds to each set of
     * balances.
     * [Optional]
     * Default: false
     * @var bool
     */
    public $withCurrencyAccountId;

    /**
     * A UTC datetime to retrieve historical balances at a specific point in time. Must be in the
     * past. If omitted, the response returns live balances.
     * [Optional]
     * Format: date-time (RFC 3339)
     * @var \DateTime
     */
    public $balancesAt;
}
