<?php

namespace Checkout\Tests\Payments;

use Checkout\JsonSerializer;
use Checkout\Payments\Request\PayoutRequest;
use Checkout\Payments\Sender\PaymentCorporateSender;
use Checkout\Payments\Sender\PaymentGovernmentSender;
use Checkout\Payments\Sender\PaymentIndividualSender;
use Checkout\Payments\Sender\PaymentInstrumentSender;
use PHPUnit\Framework\TestCase;

class PaymentSenderReferenceSerializationTest extends TestCase
{
    /**
     * reference is required on every card payout sender (min 5, max 15, alphanumeric) and on every
     * bank payout sender.
     */
    const REFERENCE = "8285282045818";

    public function senders()
    {
        return array(
            "individual" => array(new PaymentIndividualSender(), "individual"),
            "corporate" => array(new PaymentCorporateSender(), "corporate"),
            "government" => array(new PaymentGovernmentSender(), "government"),
            "instrument" => array(new PaymentInstrumentSender(), "instrument"),
        );
    }

    /**
     * @dataProvider senders
     */
    public function testSenderSerializesReferenceInPayoutRequest($sender, $type)
    {
        $sender->reference = self::REFERENCE;
        $request = new PayoutRequest();
        $request->sender = $sender;

        $decoded = json_decode((new JsonSerializer())->serialize($request), true);

        $this->assertSame(array("type" => $type, "reference" => self::REFERENCE), $decoded["sender"]);
    }

    /**
     * @dataProvider senders
     */
    public function testSenderReferenceRoundTrip($sender, $type)
    {
        $sender->reference = self::REFERENCE;
        $serializer = new JsonSerializer();

        $decoded = $serializer->deserialize($serializer->serialize($sender));

        $this->assertSame($sender->reference, $decoded["reference"]);
        $this->assertSame($type, $decoded["type"]);
    }

    public function testUnsetReferenceIsOmitted()
    {
        $decoded = json_decode((new JsonSerializer())->serialize(new PaymentInstrumentSender()), true);

        $this->assertSame(array("type" => "instrument"), $decoded);
    }
}
