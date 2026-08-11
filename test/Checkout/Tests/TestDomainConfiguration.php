<?php

namespace Checkout\Tests;

use Checkout\AbstractCheckoutSdkBuilder;

/**
 * Every client the suite builds has to choose a domain now that the merchant-specific subdomain
 * is mandatory, so they all come through here. There are deliberately two modes.
 *
 * Default: the shared hosts. The sandbox OAuth clients are not provisioned for the
 * merchant-specific subdomain, so pointing the token request at
 * {subdomain}.access.sandbox.checkout.com returns invalid_client for every integration test.
 *
 * Opt-in: set CHECKOUT_TEST_USE_SUBDOMAIN=true and the suite runs against
 * CHECKOUT_MERCHANT_SUBDOMAIN instead, exercising end to end the path merchants are being moved
 * to. Once sandbox is provisioned like production, set that variable in the workflows and this
 * becomes the mode CI runs in. The switch is deliberately separate from
 * CHECKOUT_MERCHANT_SUBDOMAIN, which CI already exports, so provisioning drives the change rather
 * than the presence of a secret.
 */
final class TestDomainConfiguration
{
    public static function useSubdomain()
    {
        return strtolower((string)getenv("CHECKOUT_TEST_USE_SUBDOMAIN")) === "true";
    }

    /**
     * @param AbstractCheckoutSdkBuilder $builder
     * @return AbstractCheckoutSdkBuilder
     */
    public static function configureDomain($builder)
    {
        $subdomain = getenv("CHECKOUT_MERCHANT_SUBDOMAIN");
        if (self::useSubdomain() && is_string($subdomain) && trim($subdomain) !== "") {
            return $builder->environmentSubdomain($subdomain);
        }
        return $builder->useLegacyDomain();
    }
}
