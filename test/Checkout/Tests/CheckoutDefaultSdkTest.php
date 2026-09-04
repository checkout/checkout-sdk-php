<?php

namespace Checkout\Tests;

use Checkout\Apm\Bacs\BacsClient;
use Checkout\Apm\CheckoutApmApi;
use Checkout\CheckoutApi;
use Checkout\Previous\CheckoutApi as PreviousCheckoutApi;
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
                ->environmentSubdomain("123dmain")
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
                ->environmentSubdomain("123dmain")
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
    public function shouldCreateCheckoutSdkWithLegacyDomain()
    {
        $checkoutApi = CheckoutSdk::builder()
            ->staticKeys()
            ->publicKey(parent::$validDefaultPk)
            ->secretKey(parent::$validDefaultSk)
            ->environment(Environment::sandbox())
            ->useLegacyDomain()
            ->build();
        $this->assertNotNull($checkoutApi);
    }

    /**
     * @test
     */
    public function shouldFailCreatingCheckoutSdkWithoutSubdomainOrLegacyDomain()
    {
        try {
            CheckoutSdk::builder()
                ->staticKeys()
                ->publicKey(parent::$validDefaultPk)
                ->secretKey(parent::$validDefaultSk)
                ->environment(Environment::sandbox())
                ->build();
            $this->fail();
        } catch (Exception $e) {
            $this->assertTrue($e instanceof CheckoutArgumentException);
            $this->assertStringContainsString("environmentSubdomain is required", $e->getMessage());
        }
    }

    /**
     * @test
     */
    public function shouldFailCreatingCheckoutSdkWithBothSubdomainAndLegacyDomain()
    {
        try {
            CheckoutSdk::builder()
                ->staticKeys()
                ->publicKey(parent::$validDefaultPk)
                ->secretKey(parent::$validDefaultSk)
                ->environment(Environment::sandbox())
                ->environmentSubdomain("123dmain")
                ->useLegacyDomain()
                ->build();
            $this->fail();
        } catch (Exception $e) {
            $this->assertTrue($e instanceof CheckoutArgumentException);
            $this->assertStringContainsString("cannot both be set", $e->getMessage());
        }
    }

    /**
     * @test
     */
    public function shouldFailCreatingCheckoutSdkWithInvalidSubdomain()
    {
        try {
            CheckoutSdk::builder()
                ->staticKeys()
                ->publicKey(parent::$validDefaultPk)
                ->secretKey(parent::$validDefaultSk)
                ->environment(Environment::sandbox())
                ->environmentSubdomain("not a subdomain")
                ->build();
            $this->fail();
        } catch (Exception $e) {
            $this->assertTrue($e instanceof CheckoutArgumentException);
            $this->assertStringContainsString("invalid environment subdomain", $e->getMessage());
        }
    }

    /**
     * @test
     * @throws CheckoutArgumentException
     */
    public function shouldInstantiateClientWithCustomHttpClient()
    {
        $httpBuilder = $this->createMock(HttpClientBuilderInterface::class);
        $httpBuilder->expects($this->exactly(6))->method("getClient");

        $this->assertNotNull(CheckoutSdk::builder()
            ->staticKeys()
            ->publicKey(parent::$validDefaultPk)
            ->secretKey(parent::$validDefaultSk)
            ->environment(Environment::sandbox())
            ->environmentSubdomain("123dmain")
            ->httpClientBuilder($httpBuilder)
            ->build());
    }

    /**
     * @test
     * @throws CheckoutArgumentException
     */
    public function shouldExposeTheBacsClient()
    {
        // Nothing else asserts the client getters, so a missing constructor assignment would leave
        // getBacsClient() returning null without any test failing.
        $checkoutApi = CheckoutSdk::builder()
            ->staticKeys()
            ->publicKey(parent::$validDefaultPk)
            ->secretKey(parent::$validDefaultSk)
            ->environment(Environment::sandbox())
            ->environmentSubdomain("123dmain")
            ->build();

        $this->assertNotNull($checkoutApi->getBacsClient());
        $this->assertInstanceOf(BacsClient::class, $checkoutApi->getBacsClient());
    }

    /**
     * @test
     * @throws CheckoutArgumentException
     */
    public function shouldNotExposeTheBacsClientOnThePreviousApi()
    {
        // POST /apms/bacs/notifications is a current-platform, secret-key-only endpoint, so the
        // client must not be reachable through the previous API surface.
        $this->assertFalse(method_exists(PreviousCheckoutApi::class, "getBacsClient"));
        $this->assertFalse(method_exists(CheckoutApmApi::class, "getBacsClient"));
        $this->assertTrue(method_exists(CheckoutApi::class, "getBacsClient"));
    }

}
