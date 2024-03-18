<?php

namespace Checkout\Tests;

use Checkout\CheckoutArgumentException;
use Checkout\CheckoutSdk;
use Checkout\Environment;
use Checkout\HttpClientBuilderInterface;
use Exception;

class CheckoutDefaultSdkTest extends UnitTestFixture
{

    /**
     * @test
     * @throws CheckoutArgumentException
     */
    public function shouldCreateCheckoutSdks()
    {
        $checkoutApi1 = CheckoutSdk::builder()
            ->staticKeys()
            ->publicKey(parent::$validDefaultPk)
            ->secretKey(parent::$validDefaultSk)
            ->environment(Environment::sandbox())
            ->build();
        $this->assertNotNull($checkoutApi1);

        $checkoutApi2 = CheckoutSdk::builder()
            ->staticKeys()
            ->secretKey(parent::$validDefaultSk)
            ->environment(Environment::sandbox())
            ->build();
        $this->assertNotNull($checkoutApi2);
    }

    /**
     * @test
     * @throws CheckoutArgumentException
     */
    public function shouldCreateCheckoutSdksWithSubdomains()
    {
        $checkoutApi1 = CheckoutSdk::builder()
            ->staticKeys()
            ->publicKey(parent::$validDefaultPk)
            ->secretKey(parent::$validDefaultSk)
            ->environment(Environment::sandbox())
            ->environmentSubdomain("123dmain")
            ->build();

        $this->assertNotNull($checkoutApi1);

        $checkoutApi2 = CheckoutSdk::builder()
            ->staticKeys()
            ->secretKey(parent::$validDefaultSk)
            ->environment(Environment::sandbox())
            ->environmentSubdomain("123dmain")
            ->build();
        $this->assertNotNull($checkoutApi2);
    }

    /**
     * @test
     */
    public function shouldFailCreatingCheckoutSdks()
    {
        try {
            CheckoutSdk::builder()
                ->staticKeys()
                ->publicKey(parent::$invalidDefaultPk)
                ->secretKey(parent::$validDefaultSk)
                ->environment(Environment::sandbox())
                ->build();
            $this->fail();
        } catch (Exception $e) {
            $this->assertTrue($e instanceof CheckoutArgumentException);
            $this->assertEquals("invalid public key", $e->getMessage());
        }

        try {
            CheckoutSdk::builder()
                ->staticKeys()
                ->publicKey(parent::$validDefaultPk)
                ->secretKey(parent::$invalidDefaultSk)
                ->environment(Environment::sandbox())
                ->build();
            $this->fail();
        } catch (Exception $e) {
            $this->assertTrue($e instanceof CheckoutArgumentException);
            $this->assertEquals("invalid secret key", $e->getMessage());
        }
    }

    /**
     * @test
     * @throws CheckoutArgumentException
     */
    public function shouldInstantiateClientWithCustomHttpClient()
    {
        $httpBuilder = $this->createMock(HttpClientBuilderInterface::class);
        $httpBuilder->expects($this->exactly(4))->method("getClient");

        $this->assertNotNull(CheckoutSdk::builder()
            ->staticKeys()
            ->publicKey(parent::$validDefaultPk)
            ->secretKey(parent::$validDefaultSk)
            ->environment(Environment::sandbox())
            ->httpClientBuilder($httpBuilder)
            ->build());
    }

}
