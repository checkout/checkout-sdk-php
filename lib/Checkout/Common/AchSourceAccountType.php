<?php

namespace Checkout\Common;

/**
 * The type of Direct Debit account on an ACH payment source.
 *
 * `PaymentRequestAchSource` is the only schema declaring this set of values. Two
 * neighbouring types are deliberately different and are not interchangeable:
 *
 * - {@see AccountType} is savings / current / cash and serves the bank-account
 *   instrument and destination positions, so it cannot express `checking`.
 * - {@see \Checkout\Instruments\AchAccountType} is savings / checking and serves the
 *   stored ACH instrument positions, so it does not declare `cash`.
 */
class AchSourceAccountType
{
    public static $savings = "savings";
    public static $checking = "checking";
    public static $cash = "cash";
}
