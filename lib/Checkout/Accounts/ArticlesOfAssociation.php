<?php

namespace Checkout\Accounts;

/**
 * Memorandum or Articles of Association document.
 */
class ArticlesOfAssociation
{
    /**
     * The type of document used.
     * [Required]
     * Enum: "memorandum_of_association", "articles_of_association"
     *
     * @var string value of ArticlesOfAssociationType
     */
    public $type;

    /**
     * The ID of the front side of the document as represented within Checkout.com systems.
     * [Required]
     * ^file_[a-z2-7]{26}$
     * Length: 31 characters
     *
     * @var string
     */
    public $front;
}
