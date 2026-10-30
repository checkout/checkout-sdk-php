<?php

namespace Checkout\Tests\Payments\Request;

use Checkout\JsonSerializer;
use Checkout\Payments\Hosted\HostedPaymentsSessionRequest;
use Checkout\Payments\Links\PaymentLinkRequest;
use Checkout\Payments\Request\CustomerRetry;
use Checkout\Payments\Request\DowntimeRetryRequest;
use Checkout\Payments\Request\DunningRetryRequest;
use Checkout\Payments\Request\PaymentRequest;
use Checkout\Payments\Request\PaymentRetryRequest;
use Checkout\Payments\Sessions\PaymentSessionsRequest;
use PHPUnit\Framework\TestCase;

class RetryRequestsSerializationTest extends TestCase
{
    private function encode($object)
    {
        return json_decode((new JsonSerializer())->serialize($object), true);
    }

    private function buildRetry($enabled)
    {
        $dunning = new DunningRetryRequest();
        $dunning->enabled = $enabled;
        $dunning->max_attempts = 6;
        $dunning->end_after_days = 30;

        $downtime = new DowntimeRetryRequest();
        $downtime->enabled = $enabled;

        $retry = new PaymentRetryRequest();
        $retry->enabled = $enabled;
        $retry->dunning = $dunning;
        $retry->downtime = $downtime;
        return $retry;
    }

    public function testPaymentRetryRequestSerializesAllProperties()
    {
        $request = new PaymentRequest();
        $request->retry = $this->buildRetry(true);

        $decoded = $this->encode($request);

        $this->assertSame(
            [
                'enabled' => true,
                'dunning' => ['enabled' => true, 'max_attempts' => 6, 'end_after_days' => 30],
                'downtime' => ['enabled' => true],
            ],
            $decoded['retry']
        );
    }

    public function testPaymentRetryRequestSerializesEnabledFalse()
    {
        $decoded = $this->encode($this->buildRetry(false));

        $this->assertArrayHasKey('enabled', $decoded);
        $this->assertFalse($decoded['enabled']);
        $this->assertFalse($decoded['dunning']['enabled']);
        $this->assertFalse($decoded['downtime']['enabled']);
    }

    public function testPaymentRetryRequestHasOnlySpecProperties()
    {
        $this->assertSame(
            ['enabled', 'dunning', 'downtime'],
            array_keys(get_class_vars(PaymentRetryRequest::class))
        );
        $this->assertSame(
            ['enabled', 'max_attempts', 'end_after_days'],
            array_keys(get_class_vars(DunningRetryRequest::class))
        );
        $this->assertSame(['enabled'], array_keys(get_class_vars(DowntimeRetryRequest::class)));
        $this->assertSame(['max_attempts'], array_keys(get_class_vars(CustomerRetry::class)));
    }

    public function testCustomerRetryRoundtripWithSwaggerExample()
    {
        $json = '{"max_attempts":2}';
        $customerRetry = new CustomerRetry();
        foreach (json_decode($json, true) as $key => $value) {
            $customerRetry->$key = $value;
        }

        $this->assertSame(2, $customerRetry->max_attempts);
        $this->assertSame($json, (new JsonSerializer())->serialize($customerRetry));
    }

    public function testPaymentRetryRequestRoundtrip()
    {
        $json = '{"enabled":true,"dunning":{"enabled":true,"max_attempts":6,"end_after_days":30},'
            . '"downtime":{"enabled":false}}';
        $data = json_decode($json, true);

        $dunning = new DunningRetryRequest();
        foreach ($data['dunning'] as $key => $value) {
            $dunning->$key = $value;
        }
        $downtime = new DowntimeRetryRequest();
        foreach ($data['downtime'] as $key => $value) {
            $downtime->$key = $value;
        }
        $retry = new PaymentRetryRequest();
        $retry->enabled = $data['enabled'];
        $retry->dunning = $dunning;
        $retry->downtime = $downtime;

        $this->assertTrue($retry->enabled);
        $this->assertSame(6, $retry->dunning->max_attempts);
        $this->assertSame(30, $retry->dunning->end_after_days);
        $this->assertFalse($retry->downtime->enabled);
        $this->assertSame($json, (new JsonSerializer())->serialize($retry));
    }

    public function customerRetryRequestProvider()
    {
        return [
            'hosted payments' => [new HostedPaymentsSessionRequest()],
            'payment links' => [new PaymentLinkRequest()],
            'payment sessions' => [new PaymentSessionsRequest()],
        ];
    }

    /**
     * @dataProvider customerRetryRequestProvider
     */
    public function testCustomerRetrySerializesMaxAttemptsOnEveryEndpoint($request)
    {
        $customerRetry = new CustomerRetry();
        $customerRetry->max_attempts = 2;
        $request->customer_retry = $customerRetry;

        $decoded = $this->encode($request);

        $this->assertSame(['max_attempts' => 2], $decoded['customer_retry']);
    }

    /**
     * @dataProvider customerRetryRequestProvider
     */
    public function testCustomerRetrySerializesZeroMaxAttempts($request)
    {
        $customerRetry = new CustomerRetry();
        $customerRetry->max_attempts = 0;
        $request->customer_retry = $customerRetry;

        $decoded = $this->encode($request);

        $this->assertSame(['max_attempts' => 0], $decoded['customer_retry']);
    }
}
