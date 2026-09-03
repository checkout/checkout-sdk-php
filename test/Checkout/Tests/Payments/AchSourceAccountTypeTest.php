<?php

namespace Checkout\Tests\Payments;

use Checkout\Common\AccountType;
use Checkout\Common\AchSourceAccountType;
use Checkout\Instruments\AchAccountType;
use Checkout\Payments\Request\Source\Apm\RequestAchSource;
use PHPUnit\Framework\TestCase;

/**
 * PaymentRequestAchSource is the only schema declaring savings / checking / cash.
 * The account_type docblock previously pointed at AccountType, which declares
 * `current` instead of `checking` - it named an enum that cannot express a valid value
 * at this position.
 */
class AchSourceAccountTypeTest extends TestCase
{
    /**
     * @test
     */
    public function shouldDeclareTheThreeValuesTheSchemaDeclares()
    {
        $this->assertEquals("savings", AchSourceAccountType::$savings);
        $this->assertEquals("checking", AchSourceAccountType::$checking);
        $this->assertEquals("cash", AchSourceAccountType::$cash);
    }

    /**
     * @test
     */
    public function shouldDifferFromTheSharedAccountType()
    {
        // The shared enum offers `current`, which this position rejects, and cannot
        // express `checking`. If these are ever unified, this fails.
        $this->assertEquals("current", AccountType::$current);
        $this->assertNotEquals(AccountType::$current, AchSourceAccountType::$checking);
        $this->assertFalse(property_exists(AchSourceAccountType::class, 'current'));
    }

    /**
     * @test
     */
    public function shouldDifferFromTheInstrumentAccountType()
    {
        // AchAccountType serves the stored ACH instrument positions: savings and
        // checking only, with no cash.
        $this->assertEquals("savings", AchAccountType::$savings);
        $this->assertEquals("checking", AchAccountType::$checking);
        $this->assertFalse(property_exists(AchAccountType::class, 'cash'));
        $this->assertTrue(property_exists(AchSourceAccountType::class, 'cash'));
    }

    /**
     * @test
     */
    public function shouldSetCheckingOnAnAchSource()
    {
        $source = new RequestAchSource();
        $source->account_type = AchSourceAccountType::$checking;
        $source->account_number = "136549956";
        $source->bank_code = "021000021";

        $this->assertEquals("checking", $source->account_type);
        $this->assertEquals("ach", $source->type);
    }
}
