<?php

namespace Checkout\Payments;

use Checkout\Payments\Previous\SenderInformation;

class ProcessingSettings
{
    /**
     * Indicates whether the payment is an Account Funding Transaction (AFT).
     * @var bool
     */
    public $aft;

    /**
     * The discount amount applied to the payment, in minor currency units.
     * @var int
     */
    public $discount_amount;

    /**
     * The shipping amount for the payment, in minor currency units.
     * @var int
     */
    public $shipping_amount;

    /**
     * The tax amount for the payment, in minor currency units.
     * @var int
     */
    public $tax_amount;

    /**
     * The invoice ID associated with the payment.
     * @var string
     */
    public $invoice_id;

    /**
     * The brand name displayed to the customer.
     * @var string
     */
    public $brand_name;

    /**
     * The locale for the payment session.
     * @var string
     */
    public $locale;

    /**
     * Partner-specific customer risk data for enhanced fraud detection.
     * @var array of PartnerCustomerRiskData
     */
    public $partner_customer_risk_data;

    /**
     * Custom payment method IDs to be used for this payment.
     * @var array of string
     */
    public $custom_payment_method_ids;

    /**
     * Airline-specific data for travel payments.
     * @var array of AirlineData
     */
    public $airline_data;

    /**
     * Accommodation-specific data for hotel payments.
     * @var array of AccommodationData
     */
    public $accommodation_data;

    /**
     * The order ID associated with the payment.
     * @var string
     */
    public $order_id;

    /**
     * The surcharge amount applied to the payment, in minor currency units.
     * @var int
     */
    public $surcharge_amount;

    /**
     * The duty amount for the payment, in minor currency units.
     * @var int
     */
    public $duty_amount;

    /**
     * The tax amount applied to shipping, in minor currency units.
     * @var int
     */
    public $shipping_tax_amount;

    /**
     * The two-letter ISO country code where the purchase was made.
     * @var string values of Country
     */
    public $purchase_country;

    /**
     * The reason for a merchant-initiated payment.
     * @var string value of MerchantInitiatedReason
     */
    public $merchant_initiated_reason;

    /**
     * The campaign ID associated with the payment.
     * @var int
     */
    public $campaign_id;

    /**
     * The original order amount before any modifications, in minor currency units.
     * @var int
     */
    public $original_order_amount;

    /**
     * The receipt ID for the payment.
     * @var string
     */
    public $receipt_id;

    /**
     * The merchant callback URL for payment notifications.
     * @var string
     */
    public $merchant_callback_url;

    /**
     * The line of business for the payment (e.g., Flights).
     * @var string
     */
    public $line_of_business;

    /**
     * The preference for PAN type (fpan for Full PAN).
     * @var string
     */
    public $pan_preference;

    /**
     * Whether to provision a network token for the payment.
     * @var bool
     */
    public $provision_network_token;

    /**
     * The preferred card scheme for processing.
     * @var string value of PreferredSchema
     */
    public $preferred_scheme;

    /**
     * The type of product being purchased.
     * @var string value of ProductType
     */
    public $product_type;

    /**
     * The Open ID for the payment.
     * @var string
     */
    public $open_id;

    /**
     * The type of terminal used for the payment.
     * @var string value of TerminalType
     */
    public $terminal_type;

    /**
     * The operating system type of the device.
     * @var string value of OsType
     */
    public $os_type;

    /**
     * The shipping preference for the payment.
     * @var string value of ShippingPreference
     */
    public $shipping_preference;

    /**
     * The user action required for the payment.
     * @var string value of UserAction
     */
    public $user_action;

    /**
     * Additional transaction context information.
     * @var array
     */
    public $set_transaction_context;

    /**
     * The OTP (One-Time Password) value for authentication.
     * @var string
     */
    public $otp_value;

    /**
     * The shipping delay in days.
     * @var int
     */
    public $shipping_delay;

    /**
     * Additional shipping information for the payment.
     * @var array of ShippingInfo
     */
    public $shipping_info;

    /**
     * DLocal-specific processing settings.
     * @var DLocalProcessingSettings
     */
    public $dlocal;

    /**
     * Aggregator information for marketplace payments.
     * @var Aggregator
     */
    public $aggregator;

    /**
     * Sender information for the payment.
     * @var SenderInformation
     */
    public $senderInformation;

    /**
     * The purpose of the payment.
     * @var string
     */
    public $purpose;

    /**
     * @var string
     */
    public $affiliate_id;

    /**
     * @var string
     */
    public $affiliate_url;

}
