<?php

namespace Checkout\Instruments;

/**
 * The type of Direct Debit account of an ACH instrument.
 *
 * Shared by the store, update and retrieve ACH instrument variants, which all declare the same two
 * values. Do not use Checkout\Common\AccountType, which declares savings, current and cash.
 */
class AchAccountType
{
    public static $savings = "savings";

    public static $checking = "checking";
}
