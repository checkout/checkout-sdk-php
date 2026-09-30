<?php

namespace Checkout\Accounts;

/**
 * The document types accepted as company verification when onboarding a sub-entity.
 *
 * incorporation_document is accepted on every variant that takes company_verification;
 * articles_of_association only on the US Company (2.0) variants. The articles of association
 * sent as their own document use ArticlesOfAssociationType instead.
 */
class CompanyVerificationType
{
    public static $incorporation_document = "incorporation_document";
    public static $articles_of_association = "articles_of_association";
}
