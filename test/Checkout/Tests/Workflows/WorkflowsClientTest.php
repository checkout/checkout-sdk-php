<?php

namespace Checkout\Tests\Workflows;

use Checkout\CheckoutApiException;
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
    /**
     * @var WorkflowsClient
     */
    private $client;

    /**
     * @before
     */
    public function init()
    {
        $this->initMocks(PlatformType::$default);
        $this->client = new WorkflowsClient($this->apiClient, $this->configuration);
    }

    /**
     * @test
     * @throws CheckoutApiException
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
     * @throws CheckoutApiException
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
     * @throws CheckoutApiException
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
     * @throws CheckoutApiException
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
     * @throws CheckoutApiException
     */
    public function shouldRemoveWorkflow()
    {
        $this->apiClient->method("delete")
            ->willReturn("response");

        $response = $this->client->removeWorkflow("workflow_id");
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldUpdateWorkflowAction()
    {
        $this->apiClient->method("put")
            ->willReturn("response");

        $response = $this->client->updateWorkflowAction("workflow_id", "action_id", new WebhookWorkflowActionRequest());
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldUpdateWorkflowCondition()
    {
        $this->apiClient->method("put")
            ->willReturn("response");

        $response = $this->client->updateWorkflowCondition("workflow_id", "action_id", new EntityWorkflowConditionRequest());
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
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
     * @throws CheckoutApiException
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
     * @throws CheckoutApiException
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
     * @throws CheckoutApiException
     */
    public function reflowByEvent()
    {
        $this->apiClient->method("post")
            ->willReturn("response");

        $response = $this->client->reflowByEvent("event_id");
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function reflowBySubject()
    {
        $this->apiClient->method("post")
            ->willReturn("response");

        $response = $this->client->reflowBySubject("subject_id");
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function reflowByEventAndWorkflow()
    {
        $this->apiClient->method("post")
            ->willReturn("response");

        $response = $this->client->reflowByEventAndWorkflow("event_id", "workflow_id");
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function reflowBySubjectAndWorkflow()
    {
        $this->apiClient->method("post")
            ->willReturn("response");

        $response = $this->client->reflowBySubjectAndWorkflow("subject_id", "workflow_id");
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function reflow()
    {
        $this->apiClient->method("post")
            ->willReturn("response");

        $response = $this->client->reflow(new ReflowByEventsRequest());
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
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
