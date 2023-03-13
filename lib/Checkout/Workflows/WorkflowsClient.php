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
     * @return array
     * @throws CheckoutApiException
     */
    public function getWorkflows()
    {
        return $this->apiClient->get(self::WORKFLOWS_PATH, $this->sdkAuthorization());
    }

    /**
     * @param CreateWorkflowRequest $createWorkflowRequest
     * @return array
     * @throws CheckoutApiException
     */
    public function createWorkflow(CreateWorkflowRequest $createWorkflowRequest)
    {
        return $this->apiClient->post(self::WORKFLOWS_PATH, $createWorkflowRequest, $this->sdkAuthorization());
    }

    /**
     * @param $workflowId
     * @return array
     * @throws CheckoutApiException
     */
    public function getWorkflow($workflowId)
    {
        return $this->apiClient->get($this->buildPath(self::WORKFLOWS_PATH, $workflowId), $this->sdkAuthorization());
    }

    /**
     * @param $workflowId
     * @return array
     * @throws CheckoutApiException
     */
    public function removeWorkflow($workflowId)
    {
        return $this->apiClient->delete($this->buildPath(self::WORKFLOWS_PATH, $workflowId), $this->sdkAuthorization());
    }

    /**
     * @param $workflowId
     * @param UpdateWorkflowRequest $updateWorkflowRequest
     * @return array
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
     * @param WorkflowActionRequest $workflowActionRequest
     * @return array
     * @throws CheckoutApiException
     */
    public function addWorkflowAction($workflowId, WorkflowActionRequest $workflowActionRequest)
    {
        return $this->apiClient->post(
            $this->buildPath(self::WORKFLOWS_PATH, $workflowId, self::ACTIONS_PATH),
            $workflowActionRequest,
            $this->sdkAuthorization()
        );
    }

    /**
     * @param $workflowId
     * @param $actionId
     * @param WorkflowActionRequest $workflowActionRequest
     * @return array
     * @throws CheckoutApiException
     */
    public function updateWorkflowAction($workflowId, $actionId, WorkflowActionRequest $workflowActionRequest)
    {
        return $this->apiClient->put(
            $this->buildPath(self::WORKFLOWS_PATH, $workflowId, self::ACTIONS_PATH, $actionId),
            $workflowActionRequest,
            $this->sdkAuthorization()
        );
    }

    /**
     * @param $workflowId
     * @param $actionId
     * @return array
     * @throws CheckoutApiException
     */
    public function removeWorkflowAction($workflowId, $actionId)
    {
        return $this->apiClient->delete(
            $this->buildPath(self::WORKFLOWS_PATH, $workflowId, self::ACTIONS_PATH, $actionId),
            $this->sdkAuthorization()
        );
    }

    /**
     * @param $workflowId
     * @param WorkflowConditionRequest $workflowConditionRequest
     * @return array
     * @throws CheckoutApiException
     */
    public function addWorkflowCondition($workflowId, WorkflowConditionRequest $workflowConditionRequest)
    {
        return$this->apiClient->post(
            $this->buildPath(self::WORKFLOWS_PATH, $workflowId, self::CONDITIONS_PATH),
            $workflowConditionRequest,
            $this->sdkAuthorization()
        );
    }

    /**
     * @param $workflowId
     * @param $conditionId
     * @param WorkflowConditionRequest $workflowConditionRequest
     * @return array
     * @throws CheckoutApiException
     */
    public function updateWorkflowCondition(
        $workflowId,
        $conditionId,
        WorkflowConditionRequest $workflowConditionRequest
    ) {
        return$this->apiClient->put(
            $this->buildPath(self::WORKFLOWS_PATH, $workflowId, self::CONDITIONS_PATH, $conditionId),
            $workflowConditionRequest,
            $this->sdkAuthorization()
        );
    }

    /**
     * @param $workflowId
     * @param $conditionId
     * @return array
     * @throws CheckoutApiException
     */
    public function removeWorkflowCondition($workflowId, $conditionId)
    {
        return$this->apiClient->delete(
            $this->buildPath(self::WORKFLOWS_PATH, $workflowId, self::CONDITIONS_PATH, $conditionId),
            $this->sdkAuthorization()
        );
    }

    /**
     * @return array
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
     * @param $eventId
     * @return array
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
     * @param $actionId
     * @return array
     * @throws CheckoutApiException
     */
    public function getActionInvocations($eventId, $actionId)
    {
        return $this->apiClient->get($this->buildPath(
            self::WORKFLOWS_PATH,
            self::EVENTS_PATH,
            $eventId,
            self::ACTIONS_PATH,
            $actionId
        ), $this->sdkAuthorization());
    }

    /**
     * @param $eventId
     * @return array
     * @throws CheckoutApiException
     */
    public function reflowByEvent($eventId)
    {
        return $this->apiClient->post(
            $this->buildPath(self::WORKFLOWS_PATH, self::EVENTS_PATH, $eventId, self::REFLOW_PATH),
            null,
            $this->sdkAuthorization()
        );
    }

    /**
     * @param $eventId
     * @param $workflowId
     * @return array
     * @throws CheckoutApiException
     */
    public function reflowByEventAndWorkflow($eventId, $workflowId)
    {
        return $this->apiClient->post(
            $this->buildPath(
                self::WORKFLOWS_PATH,
                self::EVENTS_PATH,
                $eventId,
                self::WORKFLOW_PATH,
                $workflowId,
                self::REFLOW_PATH
            ),
            null,
            $this->sdkAuthorization()
        );
    }

    /**
     * @param ReflowRequest $reflowRequest
     * @return array
     * @throws CheckoutApiException
     */
    public function reflow(ReflowRequest $reflowRequest)
    {
        return $this->apiClient->post($this->buildPath(
            self::WORKFLOWS_PATH,
            self::EVENTS_PATH,
            self::REFLOW_PATH
        ), $reflowRequest, $this->sdkAuthorization());
    }

    /**
     * @param $subjectId
     * @return array
     * @throws CheckoutApiException
     */
    public function getSubjectEvents($subjectId)
    {
        return $this->apiClient->get($this->buildPath(
            self::WORKFLOWS_PATH,
            self::EVENTS_PATH,
            self::SUBJECT_PATH,
            $subjectId
        ), $this->sdkAuthorization());
    }

    /**
     * @param $subjectId
     * @return array
     * @throws CheckoutApiException
     */
    public function reflowBySubject($subjectId)
    {
        return $this->apiClient->post(
            $this->buildPath(
                self::WORKFLOWS_PATH,
                self::EVENTS_PATH,
                self::SUBJECT_PATH,
                $subjectId,
                self::REFLOW_PATH
            ),
            null,
            $this->sdkAuthorization()
        );
    }

    /**
     * @param $subjectId
     * @param $workflowId
     * @return array
     * @throws CheckoutApiException
     */
    public function reflowBySubjectAndWorkflow($subjectId, $workflowId)
    {
        return $this->apiClient->post(
            $this->buildPath(
                self::WORKFLOWS_PATH,
                self::EVENTS_PATH,
                self::SUBJECT_PATH,
                $subjectId,
                self::WORKFLOW_PATH,
                $workflowId,
                self::REFLOW_PATH
            ),
            null,
            $this->sdkAuthorization()
        );
    }
}
