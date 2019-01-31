<?php

namespace Checkout\tests\Library;

use Checkout\Library\CheckoutConfiguration;
use Checkout\tests\Helpers\CheckoutConfigurations;
use PHPUnit\Framework\TestCase;

class CheckoutConfigurationTest extends TestCase
{

    /**
     * @param string $sandbox
     * @param string $domain
     * @dataProvider providerGetApi
     */
    public function testGetApi($sandbox, $domain)
    {
        $configuration = CheckoutConfigurations::generateModel();
        $configuration->setSandbox($sandbox);

        $this->assertEquals($domain, $configuration->getAPI());
    }

    public function providerGetApi()
    {
        return array(
            array(true, CheckoutConfiguration::ENVIRONMENT_SANDBOX_DOMAIN),
            array(false, CheckoutConfiguration::ENVIRONMENT_PRODUCTION_DOMAIN)
        );
    }

    public function testSetSecretKey()
    {
        $secret = '{override_secret}';
        $configuration = CheckoutConfigurations::generateModel();

        $configuration->setSecretKey($secret);
        $this->assertEquals($secret, $configuration->getSecretKey());
    }

    public function testSetPublicKey()
    {
        $secret = '{override_public}';
        $configuration = CheckoutConfigurations::generateModel();

        $configuration->setPublicKey($secret);
        $this->assertEquals($secret, $configuration->getPublicKey());
    }
}
