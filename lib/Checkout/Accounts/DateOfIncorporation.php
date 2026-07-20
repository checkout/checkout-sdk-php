<?php

namespace Checkout\Accounts;

/**
 * The date a company was incorporated (Accounts API v3.0).
 */
class DateOfIncorporation
{
    /**
     * The day of the month the company was incorporated.
     * [Optional]
     * Range: 1 to 31
     *
     * @var int
     */
    public $day;

    /**
     * The month the company was incorporated.
     * [Required]
     * Range: 1 to 12
     *
     * @var int
     */
    public $month;

    /**
     * The year the company was incorporated.
     * [Required]
     * Range: 1500 to 2999
     *
     * @var int
     */
    public $year;
}
