<?php

namespace Checkout\Accounts;

/**
 * The document type accepted as a representative's proof of residential address when onboarding
 * a sub-entity (EEA Sole Trader, Accounts API v3.0).
 *
 * Carries the same proof_of_address value as ProofOfPrincipalAddressType, but the spec defines
 * the two as separate enums on separate documents: this one on the representative, that one on
 * the company. They are kept apart on purpose so a change to one does not silently change the
 * other.
 */
class ProofOfResidentialAddressType
{
    public static $proof_of_address = "proof_of_address";
}
