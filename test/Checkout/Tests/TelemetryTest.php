<?php

namespace Checkout\Tests;

use Checkout\CheckoutSdk;
use Checkout\Environment;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Checkout\HttpClientBuilderInterface;

class TelemetryTest extends UnitTestFixture
{
    private $container = [];
    private $mockHandler;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->container = [];
        
        $this->mockHandler = new MockHandler();
        
        $handlerStack = HandlerStack::create($this->mockHandler);
        
        $history = Middleware::history($this->container);
        $handlerStack->push($history);
    }
    
    /**
     * Counts requests that contain a specific header
     */
    private function countRequestsWithHeader($header)
    {
        $count = 0;
        foreach ($this->container as $transaction) {
            if ($transaction['request']->hasHeader($header)) {
                $count++;
            }
        }
        return $count;
    }
    
    /**
     * Creates a CheckoutApi instance with the specified telemetry setting
     */
    private function createCheckoutApi($enableTelemetry)
    {
        $handlerStack = HandlerStack::create($this->mockHandler);
        $history = Middleware::history($this->container);
        $handlerStack->push($history);

        $client = new Client(['handler' => $handlerStack]);

        // Set up the mock builder to return this client
        $httpBuilder = $this->createMock(HttpClientBuilderInterface::class);
        $httpBuilder->expects($this->once())
            ->method("getClient")
            ->willReturn($client);
        $builder = CheckoutSdk::builder()
            ->previous()
            ->staticKeys()
            ->publicKey(parent::$validPreviousPk)
            ->secretKey(parent::$validPreviousSk)
            ->httpClientBuilder($httpBuilder)
            ->environment(Environment::sandbox());
            
        if (!$enableTelemetry) {
            $builder = $builder->enableTelemetry(false);
        } else {
            $builder = $builder->enableTelemetry(true);
        }
        
        // Add mock responses to the handler for the number of requests we expect
        for ($i = 0; $i < 3; $i++) {
            $this->mockHandler->append(new Response(200, [], '{"data": []}'));
        }
        
        return $builder->build();
    }
    
    /**
     * @test
     */
    public function shouldSendTelemetryByDefault()
    {
        $checkoutApi = $this->createCheckoutApi(true);
        
        for ($i = 0; $i < 3; $i++) {
            $checkoutApi->getEventsClient()->retrieveAllEventTypes();
        }
        
        // Telemetry headers should be present in all requests except the first
        $expectedTelemetryHeaderCount = 2;
        $telemetryHeaderCount = $this->countRequestsWithHeader('cko-sdk-telemetry');
        
        $this->assertEquals(
            $expectedTelemetryHeaderCount,
            $telemetryHeaderCount,
            "Expected exactly {$expectedTelemetryHeaderCount} requests to contain the telemetry header"
        );
    }
    
    /**
     * @test
     */
    public function shouldNotSendTelemetryWhenOptedOut()
    {
        $checkoutApi = $this->createCheckoutApi(false);
        
        for ($i = 0; $i < 3; $i++) {
            $checkoutApi->getEventsClient()->retrieveAllEventTypes();
        }
        
        // No requests should contain telemetry headers
        $telemetryHeaderCount = $this->countRequestsWithHeader('cko-sdk-telemetry');
        
        $this->assertEquals(
            0,
            $telemetryHeaderCount,
            'Expected no requests to contain the telemetry header'
        );
    }
    
    /**
     * @test
     */
    public function shouldHandleTelemetryQueueAndBottleneck()
    {
        $checkoutApi = $this->createCheckoutApi(true);
        
        // Add more mock responses for the additional requests
        for ($i = 0; $i < 7; $i++) {  // 7 more to make total of 10
            $this->mockHandler->append(new Response(200, [], '{"data": []}'));
        }
        
        $numRequests = 10;
        
        for ($i = 0; $i < $numRequests; $i++) {
            $checkoutApi->getEventsClient()->retrieveAllEventTypes();
        }
        
        // Since telemetry starts being sent from the second request,
        // we expect (numRequests - 1) telemetry headers
        $expectedTelemetryHeaderCount = $numRequests - 1;
        $telemetryHeaderCount = $this->countRequestsWithHeader('cko-sdk-telemetry');
        
        $this->assertEquals(
            $expectedTelemetryHeaderCount,
            $telemetryHeaderCount,
            "Expected {$expectedTelemetryHeaderCount} requests to contain the telemetry header"
        );
    }
}
