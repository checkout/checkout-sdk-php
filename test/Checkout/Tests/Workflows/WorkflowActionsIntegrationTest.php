<?php

namespace Checkout\Tests\Workflows;

use Checkout\CheckoutApiException;

class WorkflowActionsIntegrationTest extends AbstractWorkflowIntegrationTest
{
    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetActionInvocations()
    {
        $createWorkflowResponse = $this->createWorkflow();

        $payment = $this->makeCardPayment();

        $paymentEvents = $this->retriable(
            function () use (&$payment) {
                return $this->fourApi->getWorkflowsClient()->getSubjectEvents($payment["id"]);
            }
        );
        $this->assertResponse(
            $paymentEvents,
            "data"
        );

        $workflowResponse = $this->fourApi->getWorkflowsClient()->getWorkflow($createWorkflowResponse["id"]);
        $this->assertResponse(
            $workflowResponse,
            "actions"
        );

        $actionId = $workflowResponse["actions"][0]["id"];

        $actionInvocations =
            $this->fourApi->getWorkflowsClient()->getActionInvocations($paymentEvents["data"][0]["id"], $actionId);

        $this->assertResponse(
            $actionInvocations,
            "workflow_id",
            "event_id",
            "workflow_action_id",
            "action_type",
            "status",
            "action_invocations",
            "_links"
        );
    }
}
