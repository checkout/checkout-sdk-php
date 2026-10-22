<?php

namespace Checkout\Issuing\Cards\Update;

/**
 * The optional HTTP headers formerly documented for updating a card's details. The API no
 * longer documents them and ignores them.
 *
 * Header values are sent as strings, so declare the boolean header as the string "true"
 * rather than a PHP boolean: a boolean true would be serialized as "1" and a boolean false
 * would be serialized as an empty string and dropped from the request.
 *
 * @deprecated The API no longer supports these headers; use the method without headers.
 *             Will be removed in the next major version.
 */
class CardUpdateHeaders
{
    /**
     * Deprecated. The API no longer documents this header and ignores it: the update card
     * response does not include encrypted credentials. Kept so existing callers keep working;
     * when set, it is still sent.
     * [Optional]
     * Maps to HTTP header return-encrypted-cvv.
     * Example: "true"
     *
     * @var string|null
     * @deprecated The API no longer supports this header. Will be removed in the next major version.
     */
    public $return_encrypted_cvv;

    /**
     * Deprecated. The API no longer documents this header and ignores it. Kept so existing
     * callers keep working; when set, it is still sent.
     * [Optional]
     * Maps to HTTP header Encryption-Key.
     *
     * @var string|null
     * @deprecated The API no longer supports this header. Will be removed in the next major version.
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
