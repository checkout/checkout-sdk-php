<?php

namespace Checkout\Tests;

use Checkout\CheckoutArgumentException;
use Checkout\CheckoutDefaultSdk;
use Checkout\Environment;
use Checkout\HttpClientBuilderInterface;
use Exception;

class CheckoutDefaultSdkTest extends UnitTestFixture
{

    /**
     * @test
     */
    public function shouldCreateCheckoutSdks(): void
    {
        $builder = CheckoutDefaultSdk::staticKeys();
        $builder->setPublicKey(parent::$validDefaultPk);
        $builder->setSecretKey(parent::$validDefaultSk);
        $builder->setEnvironment(Environment::sandbox());
        $this->assertNotNull($builder->build());

        $builder = CheckoutDefaultSdk::staticKeys();
        $builder->setSecretKey(parent::$validDefaultSk);
        $builder->setEnvironment(Environment::sandbox());
        $this->assertNotNull($builder->build());
    }

    /**
     * @test
     */
    public function shouldFailCreatingCheckoutSdks(): void
    {
        try {
            $builder = CheckoutDefaultSdk::staticKeys();
            $builder->setPublicKey(parent::$invalidDefaultPk);
            $builder->setSecretKey(parent::$validDefaultSk);
            $builder->setEnvironment(Environment::sandbox());
            $this->assertNotNull($builder->build());
            self::fail();
        } catch (Exception $e) {
            self::assertTrue($e instanceof CheckoutArgumentException);
            self::assertEquals("invalid public key", $e->getMessage());
        }

        try {
            $builder = CheckoutDefaultSdk::staticKeys();
            $builder->setPublicKey(parent::$validDefaultPk);
            $builder->setSecretKey(parent::$invalidDefaultSk);
            $builder->setEnvironment(Environment::sandbox());
            $this->assertNotNull($builder->build());
            self::fail();
        } catch (Exception $e) {
            self::assertTrue($e instanceof CheckoutArgumentException);
            self::assertEquals("invalid secret key", $e->getMessage());
        }

    }

    /**
     * @test
     */
    public function ShouldInstantiateClientWithCustomHttpClient(): void
    {
        $httpBuilder = $this->createMock(HttpClientBuilderInterface::class);
        $httpBuilder->expects($this->once())->method("getClient");

        $builder = CheckoutDefaultSdk::staticKeys();
        $builder->setPublicKey(parent::$validDefaultPk);
        $builder->setSecretKey(parent::$validDefaultSk);
        $builder->setEnvironment(Environment::sandbox());
        $builder->setHttpClientBuilder($httpBuilder);
        $this->assertNotNull($builder->build());
    }

}
