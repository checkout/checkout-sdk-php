<?php

namespace Checkout\Tests;

use Checkout\Common\DateOnly;
use Checkout\JsonSerializer;
use Checkout\Payments\AccommodationData;
use Checkout\Payments\CustomerSummary;
use Checkout\Payments\Request\PaymentRequest;
use DateTime;
use PHPUnit\Framework\TestCase;

/**
 * Date formatting tests for JsonSerializer.
 *
 * The swagger distinguishes `format: date` (yyyy-MM-dd) from `format: date-time` (RFC 3339). The
 * serializer previously formatted every DateTime as a full date-time, so a DateTime on a
 * date-only field produced "2023-05-01T00:00:00+0000" and the API rejected the request with
 * 400 request_body_malformed. Verified live against sandbox on 2026-09-07, before and after.
 *
 * These tests pin both halves of the behaviour so neither can regress.
 */
class DateFormatSerializationTest extends TestCase
{
    private static function encode($object): array
    {
        return json_decode((new JsonSerializer())->serialize($object), true);
    }

    public function testFormatsDateOnlyPropertyWithoutTimeComponent()
    {
        $summary = new CustomerSummary();
        $summary->registration_date = new DateTime('2023-05-01 13:59:59');

        $encoded = self::encode($summary);

        $this->assertSame('2023-05-01', $encoded['registration_date']);
    }

    public function testFormatsEveryDateOnlyPropertyOfCustomerSummary()
    {
        $summary = new CustomerSummary();
        $summary->registration_date = new DateTime('2023-05-01 13:59:59');
        $summary->first_transaction_date = new DateTime('2023-07-01 01:02:03');
        $summary->last_payment_date = new DateTime('2023-08-01 23:59:59');

        $encoded = self::encode($summary);

        $this->assertSame('2023-05-01', $encoded['registration_date']);
        $this->assertSame('2023-07-01', $encoded['first_transaction_date']);
        $this->assertSame('2023-08-01', $encoded['last_payment_date']);
    }

    public function testFormatsAccommodationDatesWithoutTimeComponent()
    {
        $accommodation = new AccommodationData();
        $accommodation->check_in_date = new DateTime('2026-05-06 12:00:00');
        $accommodation->check_out_date = new DateTime('2026-05-09 12:00:00');

        $encoded = self::encode($accommodation);

        $this->assertSame('2026-05-06', $encoded['check_in_date']);
        $this->assertSame('2026-05-09', $encoded['check_out_date']);
    }

    public function testStillFormatsDateTimePropertyWithTimeComponent()
    {
        // capture_on is format: date-time and must keep its time component.
        $request = new PaymentRequest();
        $request->capture_on = new DateTime('2026-05-06 13:59:59+00:00');

        $encoded = self::encode($request);

        $this->assertSame('2026-05-06T13:59:59+0000', $encoded['capture_on']);
    }

    public function testDateOnlyFormattingAppliesToNestedObjects()
    {
        $summary = new CustomerSummary();
        $summary->registration_date = new DateTime('2023-05-01 13:59:59');

        $customer = new \Checkout\Common\CustomerRequest();
        $customer->email = 'test@checkout.com';
        $customer->summary = $summary;

        $request = new PaymentRequest();
        $request->customer = $customer;
        $request->capture_on = new DateTime('2026-05-06 13:59:59+00:00');

        $encoded = self::encode($request);

        $this->assertSame('2023-05-01', $encoded['customer']['summary']['registration_date']);
        $this->assertSame('2026-05-06T13:59:59+0000', $encoded['capture_on']);
    }

    /**
     * Guards against a date-only property losing its #[DateOnly] annotation. Enumerates the
     * annotation by reflection rather than trusting a central list, which is the point of the
     * attribute approach.
     */
    public function testEveryKnownDateOnlyPropertyCarriesTheAttribute()
    {
        $expected = array(
            'Checkout\\Payments\\AccommodationData'                       => array('check_in_date', 'check_out_date'),
            'Checkout\\Payments\\AccommodationGuest'                      => array('date_of_birth'),
            'Checkout\\Payments\\Passenger'                               => array('date_of_birth'),
            'Checkout\\Payments\\Contexts\\PaymentContextsPassenger'      => array('date_of_birth'),
            'Checkout\\Payments\\FlightLegDetails'                        => array('departure_date'),
            'Checkout\\Payments\\Contexts\\PaymentContextsFlightLegDetails' => array('departure_date'),
            'Checkout\\Payments\\Ticket'                                  => array('issue_date'),
            'Checkout\\Payments\\Contexts\\PaymentContextsTicket'         => array('issue_date'),
            'Checkout\\Payments\\CustomerSummary'                         => array('registration_date', 'first_transaction_date', 'last_payment_date'),
            'Checkout\\Payments\\Setups\\Common\\Customer\\MerchantAccount' => array('registration_date', 'first_transaction_date', 'last_transaction_date', 'last_modified'),
            'Checkout\\Payments\\Setups\\Common\\Order\\OrderSubMerchant'   => array('registration_date'),
            'Checkout\\Payments\\Request\\Order'                         => array('service_ends_on'),
        );

        foreach ($expected as $class => $properties) {
            $reflected = new \ReflectionClass($class);
            foreach ($properties as $property) {
                $attributes = $reflected->getProperty($property)->getAttributes(DateOnly::class);
                $this->assertNotEmpty(
                    $attributes,
                    sprintf('%s::$%s must carry #[DateOnly] (swagger declares it format: date)', $class, $property)
                );
            }
        }
    }

    /**
     * The inverse guard: a format: date-time property must NOT carry the attribute, or it would
     * lose its time component on the wire.
     */
    public function testDateTimePropertyDoesNotCarryTheAttribute()
    {
        $reflected = new \ReflectionClass('Checkout\\Payments\\Request\\PaymentRequest');

        $this->assertEmpty($reflected->getProperty('capture_on')->getAttributes(DateOnly::class));
    }

    /**
     * A DateTime placed directly in a raw array has no owning class, so the attribute cannot
     * apply and it serializes as a date-time. Documented here so the limitation is explicit
     * rather than discovered.
     */
    public function testRawArrayValueFallsBackToDateTime()
    {
        $request = new PaymentRequest();
        $request->metadata = array('some_date' => new DateTime('2023-05-01 13:59:59+00:00'));

        $encoded = self::encode($request);

        $this->assertSame('2023-05-01T13:59:59+0000', $encoded['metadata']['some_date']);
    }
}
