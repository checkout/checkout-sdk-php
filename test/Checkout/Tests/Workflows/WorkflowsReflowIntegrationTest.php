<?php

namespace Checkout\Tests\Workflows;

use Checkout\CheckoutApiException;
use Checkout\Workflows\Reflows\ReflowByEventsRequest;
use Checkout\Workflows\Reflows\ReflowBySubjectsRequest;
use Closure;

class WorkflowsReflowIntegrationTest extends AbstractWorkflowIntegrationTest
{
    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldReflowByEvent()
    {
        $this->markTestSkipped("unstable");
        $this->createWorkflow();

        $payment = $this->makeCardPayment();

        $paymentEvent = $this->getSubjectEvent($payment["id"]);

        $this->fourApi->getWorkflowsClient()->reflowByEvent($paymentEvent["id"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldReflowBySubject()
    {
        $this->createWorkflow();

        $payment = $this->makeCardPayment();

        $this->retriable(
            function () use (&$payment) {
                return $this->fourApi->getWorkflowsClient()->reflowBySubject($payment["id"]);
            }
        );
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldReflowByEventAndWorkflow()
    {
        $workflow = $this->createWorkflow();

        $payment = $this->makeCardPayment();

        $paymentEvent = $this->getSubjectEvent($payment["id"]);

        $this->retriable(
            function () use (&$paymentEvent, $workflow) {
                return $this->fourApi->getWorkflowsClient()->reflowByEventAndWorkflow($paymentEvent["id"], $workflow["id"]);
            }
        );
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldReflowByEvents()
    {
        $this->markTestSkipped("unstable");
        $workflow = $this->createWorkflow();

        $payment = $this->makeCardPayment();

        $paymentEvent = $this->getSubjectEvent($payment["id"]);

        $request = new ReflowByEventsRequest();
        $request->events = [$paymentEvent["id"]];
        $request->workflows = [$workflow["id"]];

        $this->retriable(
            function () use (&$request) {
                return $this->fourApi->getWorkflowsClient()->reflow($request);
            }
        );
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldReflowBySubjectAndWorkflow()
    {
        $this->markTestSkipped("unstable");
        $workflow = $this->createWorkflow();

        $payment = $this->makeCardPayment();

        $this->retriable(
            function () use (&$payment, $workflow) {
                return $this->fourApi->getWorkflowsClient()->reflowBySubjectAndWorkflow($payment["id"], $workflow["id"]);
            }
        );
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldReflowSubjects()
    {
        $workflow = $this->createWorkflow();

        $payment = $this->makeCardPayment();

        $request = new ReflowBySubjectsRequest();
        $request->subjects = [$payment["id"]];
        $request->workflows = [$workflow["id"]];

        $this->retriable(
            function () use (&$request) {
                return $this->fourApi->getWorkflowsClient()->reflow($request);
            }
        );
    }

    private function getSubjectEvent($subjectId)
    {
        $paymentEvents = $this->retriable(
            function () use (&$subjectId) {
                return $this->fourApi->getWorkflowsClient()->getSubjectEvents($subjectId);
            },
            $this->paymentIsApproved()
        );

        $approvedEvent = $paymentEvents["data"][0];
        $this->assertResponse($approvedEvent, "id", "type", "timestamp");
        return $approvedEvent;
    }

    /**
     * @return Closure
     */
    private function paymentIsApproved()
    {
        return function ($response) {
            return array_key_exists("data", $response) && sizeof($response["data"]) == 1
                && $response["data"][0]["type"] == "payment_approved";
        };
    }
}
