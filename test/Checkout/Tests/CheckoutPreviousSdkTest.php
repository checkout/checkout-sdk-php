<?php

namespace Checkout\Tests;

use Checkout\CheckoutArgumentException;
use Checkout\CheckoutSdk;
use Checkout\Environment;
use Checkout\HttpClientBuilderInterface;
use Exception;

class CheckoutPreviousSdkTest extends UnitTestFixture
{

    /**
     * @test
     * @throws CheckoutArgumentException
     */
    public function shouldCreateCheckoutSdks()
    {

        $this->assertNotNull(CheckoutSdk::builder()
            ->previous()
            ->staticKeys()
            ->publicKey(parent::$validPreviousPk)
            ->secretKey(parent::$validPreviousSk)
            ->environment(Environment::sandbox())
            ->build());

        $this->assertNotNull(CheckoutSdk::builder()
            ->previous()
            ->staticKeys()
            ->secretKey(parent::$validPreviousSk)
            ->environment(Environment::sandbox())
            ->build());
    }

    /**
     * @test
     * @throws CheckoutArgumentException
     */
    public function shouldCreateCheckoutSdksWithSubdomains()
    {

        $checkoutApi1 = CheckoutSdk::builder()
            ->previous()
            ->staticKeys()
            ->publicKey(parent::$validPreviousPk)
            ->secretKey(parent::$validPreviousSk)
            ->environment(Environment::sandbox())
            ->environmentSubdomain("123dmain")
            ->build();

        $this->assertNotNull($checkoutApi1);

        $checkoutApi2 = CheckoutSdk::builder()
            ->previous()
            ->staticKeys()
            ->secretKey(parent::$validPreviousSk)
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
                ->previous()
                ->staticKeys()
                ->publicKey(parent::$invalidPreviousPk)
                ->secretKey(parent::$validPreviousSk)
                ->environment(Environment::sandbox())
                ->build();
            $this->fail();
        } catch (Exception $e) {
            $this->assertTrue($e instanceof CheckoutArgumentException);
            $this->assertEquals("invalid public key", $e->getMessage());
        }

        try {
            CheckoutSdk::builder()
                ->previous()
                ->staticKeys()
                ->publicKey(parent::$validPreviousPk)
                ->secretKey(parent::$invalidPreviousSk)
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
        $httpBuilder->expects($this->once())->method("getClient");

        CheckoutSdk::builder()
            ->previous()
            ->staticKeys()
            ->publicKey(parent::$validPreviousPk)
            ->secretKey(parent::$validPreviousSk)
            ->environment(Environment::sandbox())
            ->httpClientBuilder($httpBuilder)
            ->build();
    }

}
