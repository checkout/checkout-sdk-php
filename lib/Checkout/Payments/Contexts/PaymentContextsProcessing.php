<?php

namespace Checkout\Payments\Contexts;

use Checkout\Payments\AccommodationData;
use Checkout\Payments\BillingPlan;
use Checkout\Payments\PartnerCustomerRiskData;

/**
 * Settings that control how the payment context is processed.
 */
class PaymentContextsProcessing
{
    /**
     * The plan details for a recurring payment with PayPal.
     * Required when payment_type is recurring.
     * [Optional]
     *
     * Previously named $billing_plan, so the SDK sent the key "billing_plan"; the specification
     * declares this property as "plan", so the value never reached the gateway.
     *
     * @var BillingPlan
     */
    public $plan;

    /**
     * The discount amount the merchant applied to the transaction.
     * [Optional]
     * minimum 0
     *
     * @var float
     */
    public $discount_amount;

    /**
     * The total freight or shipping and handling charges for the transaction.
     * [Optional]
     *
     * @var float
     */
    public $shipping_amount;

    /**
     * The total tax amount for the transaction, in the minor currency unit.
     * [Optional]
     *
     * @var float
     */
    public $tax_amount;

    /**
     * Invoice ID number.
     * [Optional]
     *
     * @var string
     */
    public $invoice_id;

    /**
     * The label that overrides the business name in the PayPal account on the PayPal pages.
     * [Optional]
     *
     * @var string
     */
    public $brand_name;

    /**
     * The language and region of the customer in ISO 639-2 language code; the value consists of
     * language-country.
     * [Optional]
     *
     * @var string
     */
    public $locale;

    /**
     * Shipping preference.
     * [Optional]
     * One of: no_shipping, set_provided_address, get_from_file
     *
     * @var string value of ShippingPreference
     */
    public $shipping_preference;

    /**
     * Property required by PayPal to have an appropriate payment flow.
     * [Optional]
     * One of: pay_now, continue
     *
     * @var string value of UserAction
     */
    public $user_action;

    /**
     * Key-and-value pairs with merchant-specific data for the transaction.
     * [Optional]
     *
     * Uses the shared Checkout\Payments\PartnerCustomerRiskData: payment contexts and the
     * payments request schemas resolve partner_customer_risk_data to the same specification
     * shape, and maintaining two identical classes for it invited drift.
     *
     * A single object, not a collection. The specification declares it as one object with
     * `key` and `value`, even though its description calls it "an array of key-and-value
     * pairs". The sandbox accepts both shapes; this follows the declared one, which Java,
     * .NET, Go and Ruby also use.
     *
     * @var PartnerCustomerRiskData
     */
    public $partner_customer_risk_data;

    /**
     * Promo codes. They define which of the configured payment options within a payment category
     * (pay_later, pay_over_time, and so on) are shown for this purchase.
     * [Optional]
     *
     * @var string[]
     */
    public $custom_payment_method_ids;

    /**
     * Contains information about the airline ticket and flights booked by the customer.
     * [Optional]
     *
     * @var PaymentContextsAirlineData[]
     */
    public $airline_data;

    /**
     * Contains information about the accommodation booked by the customer.
     * [Optional]
     *
     * Uses the shared AccommodationData: payment contexts, POST /payments and the
     * GET /payments/{id} response all resolve accommodation_data to the same specification
     * schema.
     *
     * @var AccommodationData[]
     */
    public $accommodation_data;
}
