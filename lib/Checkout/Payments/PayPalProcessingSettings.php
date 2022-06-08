<?php

namespace Checkout\Payments;

class PayPalProcessingSettings
{
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
     * @var PayPalShippingPreference
     */
    public $shipping_preference;

    /**
     * @var PayPalUserAction
     */
    public $user_action;

    /**
     * @var array
     */
    public $set_transaction_context;

    /**
     * @var PayPalSupplementaryData
     */
    public $supplementary_data;
}
