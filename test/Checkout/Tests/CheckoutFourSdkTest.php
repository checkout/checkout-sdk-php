<?php

namespace Checkout\Tests;

use Checkout\CheckoutArgumentException;
use Checkout\CheckoutFourSdk;
use Checkout\Environment;
use Checkout\HttpClientBuilderInterface;
use Exception;

class CheckoutFourSdkTest extends UnitTestFixture
{

    /**
     * @test
     */
    public function shouldCreateCheckoutSdks(): void
    {
        $builder = CheckoutFourSdk::staticKeys();
        $builder->setPublicKey(parent::$validFourPk);
        $builder->setSecretKey(parent::$validFourSk);
        $builder->setEnvironment(Environment::sandbox());
        $this->assertNotNull($builder->build());

        $builder = CheckoutFourSdk::staticKeys();
        $builder->setSecretKey(parent::$validFourSk);
        $builder->setEnvironment(Environment::sandbox());
        $this->assertNotNull($builder->build());
    }

    /**
     * @test
     */
    public function shouldFailCreatingCheckoutSdks(): void
    {
        try {
            $builder = CheckoutFourSdk::staticKeys();
            $builder->setPublicKey(parent::$invalidFourPk);
            $builder->setSecretKey(parent::$validFourSk);
            $builder->setEnvironment(Environment::sandbox());
            $this->assertNotNull($builder->build());
            self::fail();
        } catch (Exception $e) {
            self::assertTrue($e instanceof CheckoutArgumentException);
            self::assertEquals("invalid public key", $e->getMessage());
        }

        try {
            $builder = CheckoutFourSdk::staticKeys();
            $builder->setPublicKey(parent::$validFourPk);
            $builder->setSecretKey(parent::$invalidFourSk);
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

        $builder = CheckoutFourSdk::staticKeys();
        $builder->setPublicKey(parent::$validFourPk);
        $builder->setSecretKey(parent::$validFourSk);
        $builder->setEnvironment(Environment::sandbox());
        $builder->setFilesEnvironment(Environment::sandbox());
        $builder->setHttpClientBuilder($httpBuilder);
        $this->assertNotNull($builder->build());
    }

}
