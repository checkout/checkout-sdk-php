<?php

namespace Checkout\Tests\Workflows;

use Checkout\CheckoutApiException;
use Checkout\Tests\Payments\AbstractPaymentsIntegrationTest;
use Checkout\Workflows\Actions\WebhookSignature;
use Checkout\Workflows\Actions\WebhookWorkflowActionRequest;
use Checkout\Workflows\Conditions\EntityWorkflowConditionRequest;
use Checkout\Workflows\Conditions\EventWorkflowConditionRequest;
use Checkout\Workflows\Conditions\ProcessingChannelWorkflowConditionRequest;
use Checkout\Workflows\CreateWorkflowRequest;

abstract class AbstractWorkflowIntegrationTest extends AbstractPaymentsIntegrationTest
{

    const WORKFLOW_ENTITY_ID = "ent_kidtcgc3ge5unf4a5i6enhnr5m";
    const PROCESSING_CHANNEL_ID = "pc_5jp2az55l3cuths25t5p3xhwru";
    const WORKFLOW_NAME = "testing";
    private $workflows = [];

    protected function createWorkflow()
    {
        $signature = new WebhookSignature();
        $signature->key = "8V8x0dLK%AyD*DNS8JJr";
        $signature->method = "HMACSHA256";

        $actionRequest = new WebhookWorkflowActionRequest();
        $actionRequest->url = "https://google.com/fail";
        $actionRequest->signature = $signature;

        $entityWorkflowConditionRequest = new EntityWorkflowConditionRequest();
        $entityWorkflowConditionRequest->entities = [self::WORKFLOW_ENTITY_ID];

        $eventWorkflowConditionRequest = new EventWorkflowConditionRequest();
        $eventWorkflowConditionRequest->events = [
            "gateway" => ["payment_approved",
                "payment_declined",
                "card_verification_declined",
                "card_verified",
                "payment_authorization_incremented",
                "payment_authorization_increment_declined",
                "payment_capture_declined",
                "payment_captured",
                "payment_refund_declined",
                "payment_refunded",
                "payment_void_declined",
                "payment_voided"],
            "dispute" => ["dispute_canceled",
                "dispute_evidence_required",
                "dispute_expired",
                "dispute_lost",
                "dispute_resolved",
                "dispute_won"]
        ];

        $processingChannelWorkflowConditionRequest = new ProcessingChannelWorkflowConditionRequest();
        $processingChannelWorkflowConditionRequest->processing_channels = [self::PROCESSING_CHANNEL_ID];

        $workflowRequest = new CreateWorkflowRequest();
        $workflowRequest->actions = [$actionRequest];
        $workflowRequest->conditions = [$entityWorkflowConditionRequest, $eventWorkflowConditionRequest,
            $processingChannelWorkflowConditionRequest];
        $workflowRequest->name = self::WORKFLOW_NAME;
        $workflowRequest->active = true;

        $response = $this->retriable(
            function () use (&$workflowRequest) {
                return $this->checkoutApi->getWorkflowsClient()->createWorkflow($workflowRequest);
            }
        );

        $this->assertResponse($response, "id");

        array_push($this->workflows, $response["id"]);

        return $response;
    }

    /**
     * @after
     * @throws CheckoutApiException
     */
    public function tearDownWorkflows()
    {
        foreach ($this->workflows as $workflowId) {
            $this->checkoutApi->getWorkflowsClient()->removeWorkflow($workflowId);
        }
    }
}
