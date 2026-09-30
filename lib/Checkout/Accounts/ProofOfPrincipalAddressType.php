<?php

namespace Checkout\Accounts;

/**
 * The document type accepted as proof of the company's principal place of business when
 * onboarding a sub-entity (company variants, Accounts API v3.0).
 *
 * Carries the same proof_of_address value as ProofOfResidentialAddressType, but the spec defines
 * the two as separate enums on separate documents; see that class.
 */
class ProofOfPrincipalAddressType
{
    public static $proof_of_address = "proof_of_address";
}
