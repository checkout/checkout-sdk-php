<?php

namespace Checkout\Payments;

class BillingPlanType
{
    public static $merchant_initiated_billing = "MERCHANT_INITIATED_BILLING";
    public static $merchant_initiated_billing_single_agreement = "MERCHANT_INITIATED_BILLING_SINGLE_AGREEMENT";
    public static $channel_initiated_billing = "CHANNEL_INITIATED_BILLING";
    public static $channel_initiated_billing_single_agreement = "CHANNEL_INITIATED_BILLING_SINGLE_AGREEMENT";
    public static $recurring_payments = "RECURRING_PAYMENTS";
    public static $pre_approved_payments = "PRE_APPROVED_PAYMENTS";
}
