<?php

namespace Checkout\OnboardingSimulator;

use Checkout\ApiClient;
use Checkout\AuthorizationType;
use Checkout\CheckoutApiException;
use Checkout\CheckoutConfiguration;
use Checkout\Client;
use Checkout\OnboardingSimulator\Requests\SimulatorSetRequirementsDueRequest;
use Checkout\OnboardingSimulator\Requests\SimulatorSetStatusRequest;

/**
 * OnboardingSimulatorClient enables testing account onboarding workflows in sandbox.
 */
class OnboardingSimulatorClient extends Client
{
    const SIMULATE_PATH = "simulate";
    const ENTITIES_PATH = "entities";
    const REQUIREMENTS_DUE_PATH = "requirements-due";
    const SCENARIOS_PATH = "scenarios";
    const STATUS_PATH = "status";

    public function __construct(ApiClient $apiClient, CheckoutConfiguration $configuration)
    {
        parent::__construct($apiClient, $configuration, AuthorizationType::$oAuth);
    }

    /**
     * Set one or more requirement fields as due for an entity.
     *
     * @param string $entityId
     * @param SimulatorSetRequirementsDueRequest $request
     * @return array
     * @throws CheckoutApiException
     */
    public function setRequirementsDue($entityId, SimulatorSetRequirementsDueRequest $request)
    {
        return $this->apiClient->post(
            $this->buildPath(self::SIMULATE_PATH, self::ENTITIES_PATH, $entityId, self::REQUIREMENTS_DUE_PATH),
            $request,
            $this->sdkAuthorization()
        );
    }

    /**
     * Run a specific scenario for an entity.
     *
     * @param string $entityId
     * @param string $scenarioId
     * @return array
     * @throws CheckoutApiException
     */
    public function runScenario($entityId, $scenarioId)
    {
        return $this->apiClient->post(
            $this->buildPath(self::SIMULATE_PATH, self::ENTITIES_PATH, $entityId, self::SCENARIOS_PATH, $scenarioId),
            null,
            $this->sdkAuthorization()
        );
    }

    /**
     * Set the status of an entity.
     *
     * @param string $entityId
     * @param SimulatorSetStatusRequest $request
     * @return array
     * @throws CheckoutApiException
     */
    public function setEntityStatus($entityId, SimulatorSetStatusRequest $request)
    {
        return $this->apiClient->put(
            $this->buildPath(self::SIMULATE_PATH, self::ENTITIES_PATH, $entityId, self::STATUS_PATH),
            $request,
            $this->sdkAuthorization()
        );
    }

    /**
     * List all available requirement fields that can be marked as due.
     *
     * @return array
     * @throws CheckoutApiException
     */
    public function listAvailableRequirements()
    {
        return $this->apiClient->get(
            $this->buildPath(self::SIMULATE_PATH, self::REQUIREMENTS_DUE_PATH),
            $this->sdkAuthorization()
        );
    }

    /**
     * List all available scenarios that can be run for testing.
     *
     * @return array
     * @throws CheckoutApiException
     */
    public function listScenarios()
    {
        return $this->apiClient->get(
            $this->buildPath(self::SIMULATE_PATH, self::SCENARIOS_PATH),
            $this->sdkAuthorization()
        );
    }
}
