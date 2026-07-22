<?php

namespace Checkout\Tests\Accounts;

use Checkout\Accounts\ProcessingDetails;
use Checkout\Accounts\ProcessingDetailsAch;
use Checkout\Accounts\ProcessingDetailsPayments;
use Checkout\Common\Country;
use Checkout\Common\Currency;
use Checkout\JsonSerializer;
use PHPUnit\Framework\TestCase;

class ProcessingDetailsSerializationTest extends TestCase
{
    public function testProcessingDetailsRoundTrip()
    {
        $ach = new ProcessingDetailsAch();
        $ach->annual_ach_volume = 1000000;
        $ach->average_ach_transaction_size = 5000;
        $ach->estimated_monthly_credit_volume = 100000;
        $ach->average_credit_amount = 4000;

        $payments = new ProcessingDetailsPayments();
        $payments->ach = $ach;

        $processingDetails = new ProcessingDetails();
        $processingDetails->annual_processing_volume = 2000000;
        $processingDetails->average_transaction_value = 5000;
        $processingDetails->average_order_fulfillment_time = 3;
        $processingDetails->highest_transaction_value = 25000;
        $processingDetails->currency = Currency::$GBP;
        $processingDetails->settlement_country = Country::$GB;
        $processingDetails->target_countries = array(Country::$GB);
        $processingDetails->payments = $payments;

        $decoded = json_decode((new JsonSerializer())->serialize($processingDetails), true);

        $this->assertSame(2000000, $decoded['annual_processing_volume']);
        $this->assertSame(5000, $decoded['average_transaction_value']);
        $this->assertSame(3, $decoded['average_order_fulfillment_time']);
        $this->assertSame(25000, $decoded['highest_transaction_value']);
        $this->assertSame("GBP", $decoded['currency']);
        $this->assertSame("GB", $decoded['settlement_country']);
        $this->assertSame(array("GB"), $decoded['target_countries']);

        $this->assertSame(1000000, $decoded['payments']['ach']['annual_ach_volume']);
        $this->assertSame(5000, $decoded['payments']['ach']['average_ach_transaction_size']);
        $this->assertSame(100000, $decoded['payments']['ach']['estimated_monthly_credit_volume']);
        $this->assertSame(4000, $decoded['payments']['ach']['average_credit_amount']);
    }
}
