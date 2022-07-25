<?php

namespace Checkout\Tests\Workflows;

use Checkout\CheckoutApiException;
use Closure;

class WorkflowEventsIntegrationTest extends AbstractWorkflowIntegrationTest
{

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetEventTypes()
    {
        $response = $this->checkoutApi->getWorkflowsClient()->getEventTypes();
        $this->assertResponse($response, "items");
        $eventTypes = $response["items"];
        self::assertTrue(sizeof($eventTypes) >= 8);

        foreach ($eventTypes as $eventType) {
            $this->assertResponse(
                $eventType,
                'id',
                'description',
                'display_name',
                'events'
            );
            foreach ($eventType["events"] as $event) {
                $this->assertResponse($event, "description", "display_name", "id");
            }
        }
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetSubjectEventAndEvents()
    {
        $this->createWorkflow();

        $cardPayment = $this->makeCardPayment(true);

        $paymentEvents = $this->retriable(
            function () use (&$cardPayment) {
                return $this->checkoutApi->getWorkflowsClient()->getSubjectEvents($cardPayment["id"]);
            },
            $this->thereAreTwoPaymentEvents()
        );

        foreach ($paymentEvents["data"] as $event) {
            $this->assertResponse($event, "id", "type", "timestamp");
            $getEvent = $this->checkoutApi->getWorkflowsClient()->getEvent($event["id"]);
            $this->assertResponse(
                $getEvent,
                "id",
                "source",
                "type",
                "timestamp",
                "version",
                "data"
            );
        }
    }

    /**
     * @return Closure
     */
    private function thereAreTwoPaymentEvents()
    {
        return function ($response) {
            return array_key_exists("data", $response) && sizeof($response["data"]) == 2;
        };
    }
}
