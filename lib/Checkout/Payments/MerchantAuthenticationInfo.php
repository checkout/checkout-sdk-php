<?php

namespace Checkout\Payments;

class MerchantAuthenticationInfo
{
    /**
     * The mechanism used by the cardholder to authenticate with the 3DS Requestor.
     * [Optional]
     * Enum: "no_threeds_requestor_authentication_occurred" "three3ds_requestor_own_credentials"
     *       "federated_id" "issuer_credentials" "third_party_authentication"
     *       "fido_authenticator" "fido_authenticator_fido_assurance_data_signed" "src_assurance_data"
     * @var string|null $three_ds_req_auth_method
     */
    public $three_ds_req_auth_method;

    /**
     * The UTC date and time the cardholder authenticated with the 3DS Requestor, in ISO 8601 format.
     * [Optional]
     * Format: date-time
     * @var string|null $three_ds_req_auth_timestamp
     */
    public $three_ds_req_auth_timestamp;

    /**
     * The data that documents and supports a specific authentication process.
     * [Optional]
     * max 20000 characters
     * @var string|null $three_ds_req_auth_data
     */
    public $three_ds_req_auth_data;
}
