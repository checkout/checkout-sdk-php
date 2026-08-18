<?php

namespace Checkout\Accounts;

/**
 * Memorandum or articles of association document.
 */
class ArticlesOfAssociation
{
    /**
     * @var string value of ArticlesOfAssociationType
     */
    public $type;

    /**
     * @var string the ID of the front side of the document, as returned when the file was uploaded
     */
    public $front;
}
