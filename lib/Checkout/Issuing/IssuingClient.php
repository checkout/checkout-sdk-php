<?php

namespace Checkout\Issuing;

use Checkout\ApiClient;
use Checkout\AuthorizationType;
use Checkout\CheckoutApiException;
use Checkout\CheckoutConfiguration;
use Checkout\Client;
use Checkout\Issuing\Cardholders\CardholderRequest;
use Checkout\Issuing\Cards\Create\CardRequest;
use Checkout\Issuing\Cards\Credentials\CardCredentialsQuery;
use Checkout\Issuing\Cards\Enrollment\ThreeDSEnrollmentRequest;
use Checkout\Issuing\Cards\Enrollment\UpdateThreeDSEnrollmentRequest;
use Checkout\Issuing\Cards\Revoke\RevokeCardRequest;
use Checkout\Issuing\Cards\Suspend\SuspendCardRequest;
use Checkout\Issuing\Controls\Create\CardControlRequest;
use Checkout\Issuing\Controls\Query\CardControlsQuery;
use Checkout\Issuing\Controls\Update\UpdateCardControlRequest;
use Checkout\Issuing\Testing\CardClearingAuthorizationRequest;
use Checkout\Issuing\Testing\CardIncrementAuthorizationRequest;
use Checkout\Issuing\Testing\CardAuthorizationRequest;
use Checkout\Issuing\Testing\CardReversalAuthorizationRequest;
use Checkout\Issuing\ControlGroups\Requests\CreateControlGroupRequest;
use Checkout\Issuing\ControlGroups\Requests\ControlGroupQuery;
use Checkout\Issuing\ControlProfiles\Requests\CreateControlProfileRequest;
use Checkout\Issuing\ControlProfiles\Requests\UpdateControlProfileRequest;
use Checkout\Issuing\ControlProfiles\Requests\ControlProfileQuery;

class IssuingClient extends Client
{
    const ISSUING_PATH = "issuing";
    const CARDHOLDERS_PATH = "cardholders";
    const CARDS_PATH = "cards";
    const THREE_DS_PATH = "3ds-enrollment";
    const ACTIVATE_PATH = "activate";
    const CREDENTIALS_PATH = "credentials";
    const REVOKE_PATH = "revoke";
    const SUSPEND_PATH = "suspend";
    const CONTROLS_PATH = "controls";
    const CONTROL_GROUPS_PATH = "control-groups";
    const CONTROL_PROFILES_PATH = "control-profiles";
    const ADD_PATH = "add";
    const REMOVE_PATH = "remove";
    const SIMULATE_PATH = "simulate";
    const AUTHORIZATIONS_PATH = "authorizations";
    const PRESENTMENTS_PATH = "presentments";
    const REVERSALS_PATH = "reversals";

    public function __construct(ApiClient $apiClient, CheckoutConfiguration $configuration)
    {
        parent::__construct($apiClient, $configuration, AuthorizationType::$secretKeyOrOAuth);
    }

    /**
     * @param CardholderRequest $cardholderRequest
     * @return array
     * @throws CheckoutApiException
     */
    public function createCardholder(CardholderRequest $cardholderRequest)
    {
        return $this->apiClient->post(
            $this->buildPath(self::ISSUING_PATH, self::CARDHOLDERS_PATH),
            $cardholderRequest,
            $this->sdkAuthorization()
        );
    }

    /**
     * @param $cardholderId
     * @return array
     * @throws CheckoutApiException
     */
    public function getCardholder($cardholderId)
    {
        return $this->apiClient->get(
            $this->buildPath(self::ISSUING_PATH, self::CARDHOLDERS_PATH, $cardholderId),
            $this->sdkAuthorization()
        );
    }

    /**
     * @param $cardholderId
     * @return array
     * @throws CheckoutApiException
     */
    public function getCardholderCards($cardholderId)
    {
        return $this->apiClient->get(
            $this->buildPath(self::ISSUING_PATH, self::CARDHOLDERS_PATH, $cardholderId, self::CARDS_PATH),
            $this->sdkAuthorization()
        );
    }

    /**
     * @param CardRequest $cardRequest
     * @return array
     * @throws CheckoutApiException
     */
    public function createCard(CardRequest $cardRequest)
    {
        return $this->apiClient->post(
            $this->buildPath(self::ISSUING_PATH, self::CARDS_PATH),
            $cardRequest,
            $this->sdkAuthorization()
        );
    }

    /**
     * @param $cardId
     * @return array
     * @throws CheckoutApiException
     */
    public function getCardDetails($cardId)
    {
        return $this->apiClient->get(
            $this->buildPath(self::ISSUING_PATH, self::CARDS_PATH, $cardId),
            $this->sdkAuthorization()
        );
    }

    /**
     * @param string $cardId
     * @param ThreeDSEnrollmentRequest $threeDSEnrollmentRequest
     * @return array
     * @throws CheckoutApiException
     */
    public function enrollThreeDS($cardId, ThreeDSEnrollmentRequest $threeDSEnrollmentRequest)
    {
        return $this->apiClient->post(
            $this->buildPath(self::ISSUING_PATH, self::CARDS_PATH, $cardId, self::THREE_DS_PATH),
            $threeDSEnrollmentRequest,
            $this->sdkAuthorization()
        );
    }

    /**
     * @param string $cardId
     * @param UpdateThreeDSEnrollmentRequest $threeDSEnrollmentRequest
     * @return array
     * @throws CheckoutApiException
     */
    public function updateThreeDSEnrollment($cardId, UpdateThreeDSEnrollmentRequest $threeDSEnrollmentRequest)
    {
        return $this->apiClient->patch(
            $this->buildPath(self::ISSUING_PATH, self::CARDS_PATH, $cardId, self::THREE_DS_PATH),
            $threeDSEnrollmentRequest,
            $this->sdkAuthorization()
        );
    }

    /**
     * @param $cardId
     * @return array
     * @throws CheckoutApiException
     */
    public function getCardThreeDSDetails($cardId)
    {
        return $this->apiClient->get(
            $this->buildPath(self::ISSUING_PATH, self::CARDS_PATH, $cardId, self::THREE_DS_PATH),
            $this->sdkAuthorization()
        );
    }

    /**
     * @param string $cardId
     * @return array
     * @throws CheckoutApiException
     */
    public function activateCard($cardId)
    {
        return $this->apiClient->post(
            $this->buildPath(self::ISSUING_PATH, self::CARDS_PATH, $cardId, self::ACTIVATE_PATH),
            null,
            $this->sdkAuthorization()
        );
    }

    /**
     * @param string $cardId
     * @param CardCredentialsQuery $query
     * @return array
     * @throws CheckoutApiException
     */
    public function getCardCredentials($cardId, CardCredentialsQuery $query)
    {
        return $this->apiClient->query(
            $this->buildPath(self::ISSUING_PATH, self::CARDS_PATH, $cardId, self::CREDENTIALS_PATH),
            $query,
            $this->sdkAuthorization()
        );
    }

    /**
     * @param string $cardId
     * @param RevokeCardRequest $revokeCardRequest
     * @return array
     * @throws CheckoutApiException
     */
    public function revokeCard($cardId, RevokeCardRequest $revokeCardRequest)
    {
        return $this->apiClient->post(
            $this->buildPath(self::ISSUING_PATH, self::CARDS_PATH, $cardId, self::REVOKE_PATH),
            $revokeCardRequest,
            $this->sdkAuthorization()
        );
    }

    /**
     * @param string $cardId
     * @param SuspendCardRequest $suspendCardRequest
     * @return array
     * @throws CheckoutApiException
     */
    public function suspendCard($cardId, SuspendCardRequest $suspendCardRequest)
    {
        return $this->apiClient->post(
            $this->buildPath(self::ISSUING_PATH, self::CARDS_PATH, $cardId, self::SUSPEND_PATH),
            $suspendCardRequest,
            $this->sdkAuthorization()
        );
    }

    /**
     * @param CardControlRequest $cardControlRequest
     * @return array
     * @throws CheckoutApiException
     */
    public function createControl(CardControlRequest $cardControlRequest)
    {
        return $this->apiClient->post(
            $this->buildPath(self::ISSUING_PATH, self::CONTROLS_PATH),
            $cardControlRequest,
            $this->sdkAuthorization()
        );
    }

    /**
     * @param CardControlsQuery $query
     * @return array
     * @throws CheckoutApiException
     */
    public function getCardControls(CardControlsQuery $query)
    {
        return $this->apiClient->query(
            $this->buildPath(self::ISSUING_PATH, self::CONTROLS_PATH),
            $query,
            $this->sdkAuthorization()
        );
    }

    /**
     * @param $controlId
     * @return array
     * @throws CheckoutApiException
     */
    public function getCardControlDetails($controlId)
    {
        return $this->apiClient->get(
            $this->buildPath(self::ISSUING_PATH, self::CONTROLS_PATH, $controlId),
            $this->sdkAuthorization()
        );
    }

    /**
     * @param string $controlId
     * @param UpdateCardControlRequest $updateCardControlRequest
     * @return array
     * @throws CheckoutApiException
     */
    public function updateCardControl($controlId, UpdateCardControlRequest $updateCardControlRequest)
    {
        return $this->apiClient->put(
            $this->buildPath(self::ISSUING_PATH, self::CONTROLS_PATH, $controlId),
            $updateCardControlRequest,
            $this->sdkAuthorization()
        );
    }

    /**
     * @param $controlId
     * @return array
     * @throws CheckoutApiException
     */
    public function removeCardControl($controlId)
    {
        return $this->apiClient->delete(
            $this->buildPath(self::ISSUING_PATH, self::CONTROLS_PATH, $controlId),
            $this->sdkAuthorization()
        );
    }

    /**
     * @param CardAuthorizationRequest $authorizationRequest
     * @return array
     * @throws CheckoutApiException
     */
    public function simulateAuthorization(CardAuthorizationRequest $authorizationRequest)
    {
        return $this->apiClient->post(
            $this->buildPath(self::ISSUING_PATH, self::SIMULATE_PATH, self::AUTHORIZATIONS_PATH),
            $authorizationRequest,
            $this->sdkAuthorization()
        );
    }

    /**
     * @param $authorizationId
     * @param CardIncrementAuthorizationRequest $cardIncrementAuthorizationRequest
     * @return array
     * @throws CheckoutApiException
     */
    public function simulateIncrementingAuthorization(
        $authorizationId,
        CardIncrementAuthorizationRequest $cardIncrementAuthorizationRequest
    ) {
        return $this->apiClient->post(
            $this->buildPath(
                self::ISSUING_PATH,
                self::SIMULATE_PATH,
                self::AUTHORIZATIONS_PATH,
                $authorizationId,
                self::AUTHORIZATIONS_PATH
            ),
            $cardIncrementAuthorizationRequest,
            $this->sdkAuthorization()
        );
    }

    /**
     * @param $authorizationId
     * @param CardClearingAuthorizationRequest $cardClearingAuthorizationRequest
     * @return array
     * @throws CheckoutApiException
     */
    public function simulateClearing(
        $authorizationId,
        CardClearingAuthorizationRequest $cardClearingAuthorizationRequest
    ) {
        return $this->apiClient->post(
            $this->buildPath(
                self::ISSUING_PATH,
                self::SIMULATE_PATH,
                self::AUTHORIZATIONS_PATH,
                $authorizationId,
                self::PRESENTMENTS_PATH
            ),
            $cardClearingAuthorizationRequest,
            $this->sdkAuthorization()
        );
    }

    /**
     * @param $authorizationId
     * @param CardReversalAuthorizationRequest $cardReversalAuthorizationRequest
     * @return array
     * @throws CheckoutApiException
     */
    public function simulateReversal(
        $authorizationId,
        CardReversalAuthorizationRequest $cardReversalAuthorizationRequest
    ) {
        return $this->apiClient->post(
            $this->buildPath(
                self::ISSUING_PATH,
                self::SIMULATE_PATH,
                self::AUTHORIZATIONS_PATH,
                $authorizationId,
                self::REVERSALS_PATH
            ),
            $cardReversalAuthorizationRequest,
            $this->sdkAuthorization()
        );
    }

    /**
     * Create a control group
     *
     * Creates a control group and applies it to the specified target.
     * @param CreateControlGroupRequest $createControlGroupRequest
     * @return array
     * @throws CheckoutApiException
     */
    public function createControlGroup(CreateControlGroupRequest $createControlGroupRequest)
    {
        return $this->apiClient->post(
            $this->buildPath(self::ISSUING_PATH, self::CONTROLS_PATH, self::CONTROL_GROUPS_PATH),
            $createControlGroupRequest,
            $this->sdkAuthorization()
        );
    }

    /**
     * Get a target's control groups
     *
     * Retrieves a list of control groups applied to the specified target.
     * @param ControlGroupQuery $query
     * @return array
     * @throws CheckoutApiException
     */
    public function getControlGroups(ControlGroupQuery $query)
    {
        return $this->apiClient->query(
            $this->buildPath(self::ISSUING_PATH, self::CONTROLS_PATH, self::CONTROL_GROUPS_PATH),
            $query,
            $this->sdkAuthorization()
        );
    }

    /**
     * Get control group details
     *
     * Retrieves the details of a control group you created previously.
     * @param $controlGroupId
     * @return array
     * @throws CheckoutApiException
     */
    public function getControlGroupDetails($controlGroupId)
    {
        return $this->apiClient->get(
            $this->buildPath(self::ISSUING_PATH, self::CONTROLS_PATH, self::CONTROL_GROUPS_PATH, $controlGroupId),
            $this->sdkAuthorization()
        );
    }

    /**
     * Remove a control group
     *
     * Removes the control group and all the controls it contains.
     * If you want to reapply an equivalent control group to the card, you'll need to create a new control group.
     * @param $controlGroupId
     * @return array
     * @throws CheckoutApiException
     */
    public function removeControlGroup($controlGroupId)
    {
        return $this->apiClient->delete(
            $this->buildPath(self::ISSUING_PATH, self::CONTROLS_PATH, self::CONTROL_GROUPS_PATH, $controlGroupId),
            $this->sdkAuthorization()
        );
    }

    /**
     * Create a control profile
     *
     * Creates a control profile.
     * @param CreateControlProfileRequest $createControlProfileRequest
     * @return array
     * @throws CheckoutApiException
     */
    public function createControlProfile(CreateControlProfileRequest $createControlProfileRequest)
    {
        return $this->apiClient->post(
            $this->buildPath(self::ISSUING_PATH, self::CONTROLS_PATH, self::CONTROL_PROFILES_PATH),
            $createControlProfileRequest,
            $this->sdkAuthorization()
        );
    }

    /**
     * Get all control profiles
     *
     * Retrieves a list of control profiles for the currently authenticated client, or for a specific card if a card ID is provided.
     * @param ControlProfileQuery $query
     * @return array
     * @throws CheckoutApiException
     */
    public function getControlProfiles(ControlProfileQuery $query = null)
    {
        return $this->apiClient->query(
            $this->buildPath(self::ISSUING_PATH, self::CONTROLS_PATH, self::CONTROL_PROFILES_PATH),
            $query,
            $this->sdkAuthorization()
        );
    }

    /**
     * Get control profile details
     *
     * Retrieves the details of an existing control profile.
     * @param $controlProfileId
     * @return array
     * @throws CheckoutApiException
     */
    public function getControlProfileDetails($controlProfileId)
    {
        return $this->apiClient->get(
            $this->buildPath(self::ISSUING_PATH, self::CONTROLS_PATH, self::CONTROL_PROFILES_PATH, $controlProfileId),
            $this->sdkAuthorization()
        );
    }

    /**
     * Update a control profile
     *
     * Update the control profile.
     * @param string $controlProfileId
     * @param UpdateControlProfileRequest $updateControlProfileRequest
     * @return array
     * @throws CheckoutApiException
     */
    public function updateControlProfile($controlProfileId, UpdateControlProfileRequest $updateControlProfileRequest)
    {
        return $this->apiClient->patch(
            $this->buildPath(self::ISSUING_PATH, self::CONTROLS_PATH, self::CONTROL_PROFILES_PATH, $controlProfileId),
            $updateControlProfileRequest,
            $this->sdkAuthorization()
        );
    }

    /**
     * Remove a control profile
     *
     * Removes the control profile. A control profile cannot be removed if it is used by a control.
     * @param $controlProfileId
     * @return array
     * @throws CheckoutApiException
     */
    public function removeControlProfile($controlProfileId)
    {
        return $this->apiClient->delete(
            $this->buildPath(self::ISSUING_PATH, self::CONTROLS_PATH, self::CONTROL_PROFILES_PATH, $controlProfileId),
            $this->sdkAuthorization()
        );
    }

    /**
     * Add target to control profile
     *
     * Adds a target to an existing control profile.
     * @param string $controlProfileId
     * @param string $targetId
     * @return array
     * @throws CheckoutApiException
     */
    public function addTargetToControlProfile($controlProfileId, $targetId)
    {
        return $this->apiClient->post(
            $this->buildPath(self::ISSUING_PATH, self::CONTROLS_PATH, self::CONTROL_PROFILES_PATH, $controlProfileId, self::ADD_PATH, $targetId),
            null,
            $this->sdkAuthorization()
        );
    }

    /**
     * Remove target from control profile
     *
     * Removes a target from an existing control profile.
     * @param string $controlProfileId
     * @param string $targetId
     * @return array
     * @throws CheckoutApiException
     */
    public function removeTargetFromControlProfile($controlProfileId, $targetId)
    {
        return $this->apiClient->post(
            $this->buildPath(self::ISSUING_PATH, self::CONTROLS_PATH, self::CONTROL_PROFILES_PATH, $controlProfileId, self::REMOVE_PATH, $targetId),
            null,
            $this->sdkAuthorization()
        );
    }
}
