<?php

namespace Checkout\Accounts;

/**
 * The document types accepted as a sole trader's proof of registration when onboarding a
 * sub-entity (EEA Sole Trader, Accounts API v3.0).
 */
class ProofOfRegistrationType
{
    public static $extract_from_trade_register = "extract_from_trade_register";
    public static $other = "other";
}
