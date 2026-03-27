<?php

namespace Checkout\Issuing\Transactions\Entities;

class TransactionStatus
{
    public static $authorized = "authorized";
    public static $declined = "declined";
    public static $canceled = "canceled";
    public static $cleared = "cleared";
    public static $refunded = "refunded";
    public static $disputed = "disputed";
}
