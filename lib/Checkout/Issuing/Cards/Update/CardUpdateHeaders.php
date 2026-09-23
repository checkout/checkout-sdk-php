<?php

namespace Checkout\Issuing\Cards\Update;

/**
 * The optional HTTP headers accepted when updating a card's details.
 *
 * Header values are sent as strings, so declare the boolean header as the string "true"
 * rather than a PHP boolean: a boolean true would be serialized as "1" and a boolean false
 * would be serialized as an empty string and dropped from the request.
 */
class CardUpdateHeaders
{
    /**
     * Set to "true" to retrieve the card's encrypted credentials in the response.
     * Requires an RSA public key to be provided in the Encryption-Key header.
     * [Optional]
     * Maps to HTTP header return-encrypted-cvv.
     * Example: "true"
     *
     * @var string|null
     */
    public $return_encrypted_cvv;

    /**
     * The RSA public key used to encrypt returned credentials. Required when the
     * return-encrypted-cvv header is set to "true". Provide the public key with the
     * BEGIN PUBLIC KEY and END PUBLIC KEY headers and any newline characters removed,
     * encoded as Base64.
     * [Optional]
     * Maps to HTTP header Encryption-Key.
     *
     * @var string|null
     */
    public $encryption_key;

    /**
     * Get custom header name mappings for properties that don't follow standard conversion rules.
     *
     * @return array Property name to HTTP header name mappings
     */
    public function getHeaderMappings(): array
    {
        return [
            'return_encrypted_cvv' => 'return-encrypted-cvv',
            'encryption_key' => 'Encryption-Key'
        ];
    }
}
