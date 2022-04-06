<?php

namespace Checkout\Tests\Workflows;

use Checkout\PlatformType;
use Checkout\Tests\UnitTestFixture;
use Checkout\Workflows\Actions\WebhookWorkflowActionRequest;
use Checkout\Workflows\Conditions\EntityWorkflowConditionRequest;
use Checkout\Workflows\CreateWorkflowRequest;
use Checkout\Workflows\Reflows\ReflowByEventsRequest;
use Checkout\Workflows\UpdateWorkflowRequest;
use Checkout\Workflows\WorkflowsClient;

class WorkflowsClientTest extends UnitTestFixture
{
    private $client;

    /**
     * @before
     */
    public function init()
    {
        $this->initMocks(PlatformType::$four);
        $this->client = new WorkflowsClient($this->apiClient, $this->configuration);
    }

    /**
     * @test
     */
    public function shouldCreateWorkflow()
    {
        $this->apiClient
            ->method("post")
            ->willReturn("response");

        $response = $this->client->createWorkflow(new CreateWorkflowRequest());
        $this->assertNotNull($response);
    }

    /**
     * @test
     */
    public function shouldGetWorkflows()
    {
        $this->apiClient
            ->method("get")
            ->willReturn("response");

        $response = $this->client->getWorkflows();
        $this->assertNotNull($response);
    }

    /**
     * @test
     */
    public function shouldGetWorkflow()
    {
        $this->apiClient
            ->method("get")
            ->willReturn("response");

        $response = $this->client->getWorkflow("workflow_id");
        $this->assertNotNull($response);
    }

    /**
     * @test
     */
    public function shouldUpdateWorkflow()
    {
        $this->apiClient
            ->method("patch")
            ->willReturn("response");

        $response = $this->client->updateWorkflow("workflow_id", new UpdateWorkflowRequest());
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function shouldRemoveWorkflow()
    {
        $this->apiClient->method("delete");

        $this->client->removeWorkflow("workflow_id");
    }

    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function shouldUpdateWorkflowAction()
    {
        $this->apiClient->method("put");

        $this->client->updateWorkflowAction("workflow_id", "action_id", new WebhookWorkflowActionRequest());
    }

    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function shouldUpdateWorkflowCondition()
    {
        $this->apiClient->method("put");

        $this->client->updateWorkflowCondition("workflow_id", "action_id", new EntityWorkflowConditionRequest());
    }

    /**
     * @test
     */
    public function getEventTypes()
    {
        $this->apiClient
            ->method("get")
            ->willReturn("response");

        $response = $this->client->getEventTypes();
        $this->assertNotNull($response);
    }

    /**
     * @test
     */
    public function shouldGetSubjectEvents()
    {
        $this->apiClient
            ->method("get")
            ->willReturn("response");

        $response = $this->client->getSubjectEvents("subject_id");
        $this->assertNotNull($response);
    }

    /**
     * @test
     */
    public function getEvent()
    {
        $this->apiClient
            ->method("get")
            ->willReturn("response");

        $response = $this->client->getEvent("event_id");
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function reflowByEvent()
    {
        $this->apiClient->method("post");

        $this->client->reflowByEvent("event_id");
    }

    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function reflowBySubject()
    {
        $this->apiClient->method("post");

        $this->client->reflowBySubject("subject_id");
    }

    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function reflowByEventAndWorkflow()
    {
        $this->apiClient->method("post");

        $this->client->reflowByEventAndWorkflow("event_id", "workflow_id");
    }

    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function reflowBySubjectAndWorkflow()
    {
        $this->apiClient->method("post");

        $this->client->reflowBySubjectAndWorkflow("subject_id", "workflow_id");
    }

    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function reflow()
    {
        $this->apiClient->method("post");

        $this->client->reflow(new ReflowByEventsRequest());
    }

    /**
     * @test
     */
    public function shouldGetActionInvocations()
    {
        $this->apiClient
            ->method("get")
            ->willReturn("response");

        $response = $this->client->getActionInvocations("event_id", "action_id");
        $this->assertNotNull($response);
    }
}
