<?php

namespace Checkout\Tests;

use Checkout\AbstractCheckoutSdkBuilder;

/**
 * Every client the suite builds has to choose a domain now that the merchant-specific
 * subdomain is mandatory, so they all come through here.
 *
 * The suite uses the shared hosts. It would be better to exercise the merchant-specific
 * subdomain, since that is the path merchants are being moved to, but the sandbox OAuth
 * clients are not provisioned for it: pointing the token request at
 * {subdomain}.access.sandbox.checkout.com returns invalid_client for every integration test.
 * Until those clients are bound to the subdomain, CI has to use the legacy hosts.
 */
final class TestDomainConfiguration
{
    /**
     * @param AbstractCheckoutSdkBuilder $builder
     * @return AbstractCheckoutSdkBuilder
     */
    public static function configureDomain($builder)
    {
        return $builder->useLegacyDomain();
    }
}
