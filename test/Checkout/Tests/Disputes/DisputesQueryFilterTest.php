<?php

namespace Checkout\Tests\Disputes;

use Checkout\Disputes\DisputesQueryFilter;
use DateTime;
use PHPUnit\Framework\TestCase;

class DisputesQueryFilterTest extends TestCase
{

    /**
     * @test
     */
    public function shouldGetQueryParameters()
    {
        $fromDate = DateTime::createFromFormat("j-M-Y", "10-Jan-2022");
        $fromDate->setTime(12, 12, 12);
        $toDate = DateTime::createFromFormat("Y-m-d", "2021-02-15");
        $toDate->setTime(12, 12, 12);

        $disputesQueryFilter = new DisputesQueryFilter();
        $disputesQueryFilter->limit = 100;
        $disputesQueryFilter->skip = 1;
        $disputesQueryFilter->from = $fromDate;
        $disputesQueryFilter->to = $toDate;
        $disputesQueryFilter->id = "id";
        $disputesQueryFilter->statuses = "statuses";
        $disputesQueryFilter->payment_id = "payment_id";
        $disputesQueryFilter->payment_reference = "payment_reference";
        $disputesQueryFilter->payment_arn = "payment_arn";
        $disputesQueryFilter->this_channel_only = "this_channel_only";
        $disputesQueryFilter->entity_ids = "entity_ids";
        $disputesQueryFilter->sub_entity_ids = "sub_entity_ids";
        $disputesQueryFilter->payment_mcc = "payment_mcc";

        if (version_compare(PHP_VERSION, '8.1', '<=')) {
            $expected = "limit=100&skip=1&id=id&statuses=statuses&payment_id=payment_id&payment_reference=payment_reference&payment_arn=payment_arn&this_channel_only=this_channel_only&entity_ids=entity_ids&sub_entity_ids=sub_entity_ids&payment_mcc=payment_mcc&from=2022-01-10T12%3A12%3A12%2B0000&to=2021-02-15T12%3A12%3A12%2B0000";
        } else {
            $expected = "from=2022-01-10T12%3A12%3A12%2B0000&to=2021-02-15T12%3A12%3A12%2B0000&limit=100&skip=1&id=id&statuses=statuses&payment_id=payment_id&payment_reference=payment_reference&payment_arn=payment_arn&this_channel_only=this_channel_only&entity_ids=entity_ids&sub_entity_ids=sub_entity_ids&payment_mcc=payment_mcc";
        }


        $this->assertEquals($expected, $disputesQueryFilter->getEncodedQueryParameters());
    }

    /**
     * @test
     */
    public function shouldKeepEachValueInItsOwnParameter()
    {
        $disputesQueryFilter = new DisputesQueryFilter();
        $disputesQueryFilter->statuses = "a&entity_ids=x#b";
        $disputesQueryFilter->entity_ids = "ent_1";
        $disputesQueryFilter->payment_reference = "ref 1/2?+";

        $encoded = $disputesQueryFilter->getEncodedQueryParameters();

        $this->assertSame(
            "statuses=a%26entity_ids%3Dx%23b&payment_reference=ref%201%2F2%3F%2B&entity_ids=ent_1",
            $encoded
        );
        parse_str($encoded, $parsed);
        $this->assertSame(
            ["statuses" => "a&entity_ids=x#b", "payment_reference" => "ref 1/2?+", "entity_ids" => "ent_1"],
            $parsed
        );
    }

    /**
     * @test
     */
    public function shouldSendFalseZeroAndEmptyValues()
    {
        $disputesQueryFilter = new DisputesQueryFilter();
        $disputesQueryFilter->skip = 0;
        $disputesQueryFilter->id = "";
        $disputesQueryFilter->this_channel_only = false;

        $this->assertSame("skip=0&id=&this_channel_only=false", $disputesQueryFilter->getEncodedQueryParameters());
    }

    /**
     * @test
     */
    public function shouldRenderTrueAsText()
    {
        $disputesQueryFilter = new DisputesQueryFilter();
        $disputesQueryFilter->this_channel_only = true;

        $this->assertSame("this_channel_only=true", $disputesQueryFilter->getEncodedQueryParameters());
    }

    /**
     * @test
     */
    public function shouldSendNothingWhenNoValueIsSet()
    {
        $this->assertSame("", (new DisputesQueryFilter())->getEncodedQueryParameters());
    }
}
