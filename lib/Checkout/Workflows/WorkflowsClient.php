<?php

namespace Checkout\Workflows;

use Checkout\ApiClient;
use Checkout\AuthorizationType;
use Checkout\CheckoutApiException;
use Checkout\CheckoutConfiguration;
use Checkout\Client;
use Checkout\Workflows\Actions\WorkflowActionRequest;
use Checkout\Workflows\Conditions\WorkflowConditionRequest;
use Checkout\Workflows\Reflows\ReflowRequest;

class WorkflowsClient extends Client
{
    const WORKFLOWS_PATH = "workflows";
    const ACTIONS_PATH = "actions";
    const CONDITIONS_PATH = "conditions";
    const EVENT_TYPES_PATH = "event-types";
    const EVENTS_PATH = "events";
    const SUBJECT_PATH = "subject";
    const REFLOW_PATH = "reflow";
    const WORKFLOW_PATH = "workflow";

    public function __construct(ApiClient $apiClient, CheckoutConfiguration $configuration)
    {
        parent::__construct($apiClient, $configuration, AuthorizationType::$secretKeyOrOAuth);
    }

    /**
     * @param CreateWorkflowRequest $createWorkflowRequest
     * @return mixed
     * @throws CheckoutApiException
     */
    public function createWorkflow(CreateWorkflowRequest $createWorkflowRequest)
    {
        return $this->apiClient->post(self::WORKFLOWS_PATH, $createWorkflowRequest, $this->sdkAuthorization());
    }

    /**
     * @return mixed
     * @throws CheckoutApiException
     */
    public function getWorkflows()
    {
        return $this->apiClient->get(self::WORKFLOWS_PATH, $this->sdkAuthorization());
    }

    /**
     * @param $workflowId
     * @return mixed
     * @throws CheckoutApiException
     */
    public function getWorkflow($workflowId)
    {
        return $this->apiClient->get($this->buildPath(self::WORKFLOWS_PATH, $workflowId), $this->sdkAuthorization());
    }

    /**
     * @param $workflowId
     * @param UpdateWorkflowRequest $updateWorkflowRequest
     * @return mixed
     * @throws CheckoutApiException
     */
    public function updateWorkflow($workflowId, UpdateWorkflowRequest $updateWorkflowRequest)
    {
        return $this->apiClient->patch(
            $this->buildPath(self::WORKFLOWS_PATH, $workflowId),
            $updateWorkflowRequest,
            $this->sdkAuthorization()
        );
    }

    /**
     * @param $workflowId
     * @throws CheckoutApiException
     */
    public function removeWorkflow($workflowId)
    {
        $this->apiClient->delete($this->buildPath(self::WORKFLOWS_PATH, $workflowId), $this->sdkAuthorization());
    }

    /**
     * @param $workflowId
     * @param $actionId
     * @param WorkflowActionRequest $workflowActionRequest
     * @throws CheckoutApiException
     */
    public function updateWorkflowAction($workflowId, $actionId, WorkflowActionRequest $workflowActionRequest)
    {
        $this->apiClient->put(
            $this->buildPath(self::WORKFLOWS_PATH, $workflowId, self::ACTIONS_PATH, $actionId),
            $workflowActionRequest,
            $this->sdkAuthorization()
        );
    }

    /**
     * @param $workflowId
     * @param $conditionId
     * @param WorkflowConditionRequest $workflowConditionRequest
     * @throws CheckoutApiException
     */
    public function updateWorkflowCondition($workflowId, $conditionId, WorkflowConditionRequest $workflowConditionRequest)
    {
        $this->apiClient->put(
            $this->buildPath(self::WORKFLOWS_PATH, $workflowId, self::CONDITIONS_PATH, $conditionId),
            $workflowConditionRequest,
            $this->sdkAuthorization()
        );
    }

    /**
     * @return mixed
     * @throws CheckoutApiException
     */
    public function getEventTypes()
    {
        return $this->apiClient->get(
            $this->buildPath(self::WORKFLOWS_PATH, self::EVENT_TYPES_PATH),
            $this->sdkAuthorization()
        );
    }

    /**
     * @param $subjectId
     * @return mixed
     * @throws CheckoutApiException
     */
    public function getSubjectEvents($subjectId)
    {
        return $this->apiClient->get($this->buildPath(self::WORKFLOWS_PATH, self::EVENTS_PATH, self::SUBJECT_PATH, $subjectId), $this->sdkAuthorization());
    }

    /**
     * @param $eventId
     * @return mixed
     * @throws CheckoutApiException
     */
    public function getEvent($eventId)
    {
        return $this->apiClient->get(
            $this->buildPath(self::WORKFLOWS_PATH, self::EVENTS_PATH, $eventId),
            $this->sdkAuthorization()
        );
    }

    /**
     * @param $eventId
     * @throws CheckoutApiException
     */
    public function reflowByEvent($eventId)
    {
        $this->apiClient->post(
            $this->buildPath(self::WORKFLOWS_PATH, self::EVENTS_PATH, $eventId, self::REFLOW_PATH),
            null,
            $this->sdkAuthorization()
        );
    }

    /**
     * @param $subjectId
     * @throws CheckoutApiException
     */
    public function reflowBySubject($subjectId)
    {
        $this->apiClient->post(
            $this->buildPath(self::WORKFLOWS_PATH, self::EVENTS_PATH, self::SUBJECT_PATH, $subjectId, self::REFLOW_PATH),
            null,
            $this->sdkAuthorization()
        );
    }

    /**
     * @param $eventId
     * @param $workflowId
     * @throws CheckoutApiException
     */
    public function reflowByEventAndWorkflow($eventId, $workflowId)
    {
        $this->apiClient->post(
            $this->buildPath(self::WORKFLOWS_PATH, self::EVENTS_PATH, $eventId, self::WORKFLOW_PATH, $workflowId, self::REFLOW_PATH),
            null,
            $this->sdkAuthorization()
        );
    }

    /**
     * @param $subjectId
     * @param $workflowId
     * @throws CheckoutApiException
     */
    public function reflowBySubjectAndWorkflow($subjectId, $workflowId)
    {
        $this->apiClient->post($this->buildPath(self::WORKFLOWS_PATH, self::EVENTS_PATH, self::SUBJECT_PATH, $subjectId, self::WORKFLOW_PATH, $workflowId, self::REFLOW_PATH), null, $this->sdkAuthorization());
    }

    /**
     * @param ReflowRequest $reflowRequest
     * @throws CheckoutApiException
     */
    public function reflow(ReflowRequest $reflowRequest)
    {
        $this->apiClient->post($this->buildPath(self::WORKFLOWS_PATH, self::EVENTS_PATH, self::REFLOW_PATH), $reflowRequest, $this->sdkAuthorization());
    }
}
