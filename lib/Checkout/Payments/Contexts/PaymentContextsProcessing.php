<?php

namespace Checkout\Payments\Contexts;

class PaymentContextsProcessing
{
    /**
     * @var string value of BillingPlan
     */
    public $billing_plan;

    /**
     * @var int
     */
    public $shipping_amount;

    /**
     * @var string
     */
    public $invoice_id;

    /**
     * @var string
     */
    public $brand_name;

    /**
     * @var string
     */
    public $locale;

    /**
     * @var string value of ShippingPreference
     */
    public $shipping_preference;

    /**
     * @var string value of UserAction
     */
    public $user_action;

    /**
     * @var string value of PaymentContextsPartnerCustomerRiskData
     */
    public $partner_customer_risk_data;

    /**
     * @var array of Checkout\Payments\Contexts\PaymentContextsAirlineData
     */
    public $airline_data;
}
