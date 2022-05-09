<?php

namespace Checkout\Tests\Workflows;

use Checkout\CheckoutApiException;
use Checkout\Workflows\Actions\WebhookSignature;
use Checkout\Workflows\Actions\WebhookWorkflowActionRequest;
use Checkout\Workflows\Conditions\EventWorkflowConditionRequest;
use Checkout\Workflows\Conditions\WorkflowConditionType;
use Checkout\Workflows\UpdateWorkflowRequest;
use PHPUnit\Framework\AssertionFailedError;

class WorkflowsIntegrationTest extends AbstractWorkflowIntegrationTest
{
    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreateAndGetWorkflows()
    {
        $workflow = $this->createWorkflow();

        $workflowResponse = $this->fourApi->getWorkflowsClient()->getWorkflow($workflow["id"]);

        $this->assertResponse(
            $workflowResponse,
            'id',
            'name',
            'active',
            'actions',
            'conditions'
        );

        foreach ($workflowResponse["actions"] as $action) {
            $this->assertResponse(
                $action,
                'id',
                'type',
                'url',
                'signature'
            );
        }

        foreach ($workflowResponse["conditions"] as $condition) {
            $this->assertResponse($condition, "id", "type");
            switch ($condition["type"]) {
                case WorkflowConditionType::$event:
                    $this->assertResponse($condition, "events");
                    self::assertTrue(sizeof($condition["events"]) > 0);
                    break;
                case WorkflowConditionType::$entity:
                    $this->assertResponse($condition, "entities");
                    self::assertTrue(sizeof($condition["entities"]) > 0);
                    break;
                case WorkflowConditionType::$processing_channel:
                    $this->assertResponse($condition, "processing_channels");
                    self::assertTrue(sizeof($condition["processing_channels"]) > 0);
                    break;
                default:
                    throw new AssertionFailedError("invalid workflow condition response");
            }
        }

        $workflows = $this->fourApi->getWorkflowsClient()->getWorkflows();

        foreach ($workflows["data"] as $workflow) {
            $this->assertResponse(
                $workflow,
                'id',
                'name',
                'active',
                '_links'
            );
        }
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreateAndUpdateWorkflow()
    {
        $workflow = $this->createWorkflow();

        $updateWorkflowRequest = new UpdateWorkflowRequest();
        $updateWorkflowRequest->name = "PHP testing";
        $updateWorkflowRequest->active = true;

        $updateWorkflowResponse = $this->fourApi->getWorkflowsClient()->updateWorkflow($workflow["id"], $updateWorkflowRequest);
        $this->assertResponse($updateWorkflowResponse, "name", "active", "http_metadata");
        self::assertEquals(200, $updateWorkflowResponse["http_metadata"]->getStatusCode());

        self::assertEquals($updateWorkflowRequest->name, $updateWorkflowResponse["name"]);
        self::assertEquals($updateWorkflowRequest->active, $updateWorkflowResponse["active"]);
    }

    /**
     * @test
     */
    public function shouldUpdateWorkflowAction()
    {
        $workflow = $this->createWorkflow();

        $workflowResponse = $this->fourApi->getWorkflowsClient()->getWorkflow($workflow["id"]);

        $this->assertResponse(
            $workflowResponse,
            'id',
            'name',
            'active',
            'actions',
            'conditions'
        );

        $actionId = $workflowResponse["actions"][0]["id"];

        $signature = new WebhookSignature();
        $signature->key = '8V8x0dLK%AyD*DNS8JJr';
        $signature->method = 'HMACSHA256';

        $actionRequest = new WebhookWorkflowActionRequest();
        $actionRequest->url = 'https://google.com/fail/fake';
        $actionRequest->signature = $signature;

        $updateResponse = $this->fourApi->getWorkflowsClient()->updateWorkflowAction($workflow["id"], $actionId, $actionRequest);
        self::assertArrayHasKey("http_metadata", $updateResponse);
        self::assertEquals(200, $updateResponse["http_metadata"]->getStatusCode());

        $workflowUpdated = $this->fourApi->getWorkflowsClient()->getWorkflow($workflow["id"]);
        $this->assertResponse($workflowUpdated, "actions");

        $action = $workflowUpdated["actions"][0];

        self::assertEquals($actionId, $action["id"]);
        self::assertEquals($actionRequest->url, $action["url"]);
    }

    /**
     * @test
     */
    public function shouldUpdateWorkflowCondition()
    {
        $workflow = $this->createWorkflow();

        $workflowResponse = $this->fourApi->getWorkflowsClient()->getWorkflow($workflow["id"]);

        $this->assertResponse(
            $workflowResponse,
            'id',
            'name',
            'active',
            'actions',
            'conditions'
        );

        $conditionEvent = null;
        foreach ($workflowResponse["conditions"] as $condition) {
            if ($condition["type"] == "event") {
                $conditionEvent = $condition;
            }
        }

        self::assertNotNull($conditionEvent);

        $conditionRequest = new EventWorkflowConditionRequest();
        $conditionRequest->events = [
            "gateway" => ['card_verified',
                'card_verification_declined',
                'payment_approved',
                'payment_pending',
                'payment_declined',
                'payment_voided',
                'payment_captured',
                'payment_refunded'],
            "dispute" => ['dispute_canceled',
                'dispute_evidence_required',
                'dispute_expired',
                'dispute_lost',
                'dispute_resolved',
                'dispute_won']
        ];

        $updateResponse = $this->fourApi->getWorkflowsClient()->updateWorkflowCondition($workflow["id"], $conditionEvent["id"], $conditionRequest);
        self::assertArrayHasKey("http_metadata", $updateResponse);
        self::assertEquals(200, $updateResponse["http_metadata"]->getStatusCode());

        $workflowUpdated = $this->fourApi->getWorkflowsClient()->getWorkflow($workflow["id"]);
        $this->assertResponse($workflowUpdated, "conditions");

        self::assertTrue(sizeof($workflowUpdated["conditions"]) == 3);
    }
}
