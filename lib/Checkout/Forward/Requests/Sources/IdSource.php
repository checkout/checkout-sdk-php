<?php

namespace Checkout\Forward\Requests\Sources;

class IdSource extends AbstractSource
{
    /**
     * The unique identifier of the payment instrument (Required, pattern ^(src)_(\w{26})$)
     *
     * @var string
     */
    public $id;

    /**
     * The unique token for the card's security code. Checkout.com does not store a card's CVV with its associated
     * payment instrument. To pass a CVV with your forward request, use the Frames SDK to collect and tokenize the CVV
     * and pass the value in this field. The token will replace the placeholder {{card_cvv}} value in destination
     * request.body (Optional, pattern ^(tok)_(\w{26})$)
     *
     * @var string|null
     */
    public $cvv_token;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct(SourceType::$id);
    }
}
