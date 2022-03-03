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
    public function shouldGetQueryParameters(): void
    {
        $fromDate = DateTime::createFromFormat("j-M-Y", "10-Jan-2022");
        $fromDate->setTime(12, 12, 12, 12);
        $toDate = DateTime::createFromFormat("Y-m-d", "2021-02-15");
        $toDate->setTime(12, 12, 12, 12);

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

        $expected = "limit=100&skip=1&from=2022-01-10T12%3A12%3A12%2B0000&to=2021-02-15T12%3A12%3A12%2B0000&id=id&statuses=statuses&payment_id=payment_id&payment_reference=payment_reference&payment_arn=payment_arn&this_channel_only=this_channel_only&entity_ids=entity_ids&sub_entity_ids=sub_entity_ids&payment_mcc=payment_mcc";

        self::assertEquals($expected, $disputesQueryFilter->getEncodedQueryParameters());
    }

}
