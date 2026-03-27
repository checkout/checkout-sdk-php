<?php

namespace Checkout\Issuing\Transactions\Requests;

use Checkout\Common\AbstractQueryFilter;

class TransactionsQuery extends AbstractQueryFilter
{
    /**
     * The maximum number of transactions returned (between 10-100). The default is 10.
     * @var int
     */
    public $limit;

    /**
     * The number of transactions to skip. The default is 0.
     * @var int
     */
    public $skip;

    /**
     * The cardholder's unique identifier.
     * @var string
     */
    public $cardholder_id;

    /**
     * The card's unique identifier.
     * @var string
     */
    public $card_id;

    /**
     * The entity's unique identifier.
     * @var string
     */
    public $entity_id;

    /**
     * An optional filter for the transaction lifecycle status.
     * Use TransactionStatus for possible values.
     * @var string
     */
    public $status;

    /**
     * An optional start date filter for transactions, in ISO 8601 format.
     * @var string
     */
    public $from;

    /**
     * An optional end date filter for transactions, in ISO 8601 format.
     * @var string
     */
    public $to;
}
