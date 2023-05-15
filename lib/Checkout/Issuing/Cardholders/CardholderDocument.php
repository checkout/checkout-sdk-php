<?php

namespace Checkout\Issuing\Cardholders;

use Checkout\Common\DocumentType;

class CardholderDocument
{
    /**
     * @var DocumentType
     */
    public $type;

    /**
     * @var string
     */
    public $front_document_id;

    /**
     * @var string
     */
    public $back_document_id;

}
