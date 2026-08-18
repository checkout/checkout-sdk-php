<?php

namespace Checkout\Accounts;

/**
 * The document type accepted as the memorandum or articles of association when onboarding
 * a sub-entity.
 *
 * Separate from Common\DocumentType, which lists identity documents.
 */
class ArticlesOfAssociationType
{
    public static $memorandum_of_association = "memorandum_of_association";
    public static $articles_of_association = "articles_of_association";
}
