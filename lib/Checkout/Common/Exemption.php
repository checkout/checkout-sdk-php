<?php

namespace Checkout\Common;

class Exemption
{
    public static $none = "none";
    public static $low_value = "low_value";
    public static $recurring_operation = "recurring_operation";
    public static $transaction_risk_assessment = "transaction_risk_assessment";
    public static $secure_corporate_payment = "secure_corporate_payment";
    public static $trusted_listing = "trusted_listing";
    public static $three_ds_outage = "3ds_outage";
    public static $sca_delegation = "sca_delegation";
    public static $out_of_sca_scope = "out_of_sca_scope";
    public static $other = "other";
    public static $low_risk_program = "low_risk_program";
}
