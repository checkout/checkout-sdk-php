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
use Checkout\Issuing\Cards\Update\UpdateCardRequest;
use Checkout\Issuing\Cards\Renew\RenewCardRequest;
use Checkout\Issuing\Cards\ScheduleRevocation\ScheduleRevocationRequest;
use Checkout\Issuing\Controls\Create\CardControlRequest;
use Checkout\Issuing\Controls\Query\CardControlsQuery;
use Checkout\Issuing\Controls\Update\UpdateCardControlRequest;
use Checkout\Issuing\Testing\CardClearingAuthorizationRequest;
use Checkout\Issuing\Testing\CardIncrementAuthorizationRequest;
use Checkout\Issuing\Testing\CardAuthorizationRequest;
use Checkout\Issuing\Testing\CardReversalAuthorizationRequest;
use Checkout\Issuing\Testing\SimulateOobAuthenticationRequest;
use Checkout\Issuing\Testing\SimulateRefundRequest;
use Checkout\Issuing\CardholderAccessTokens\CardholderAccessTokenRequest;
use Checkout\Issuing\Cardholders\UpdateCardholderRequest;
use Checkout\Issuing\ControlGroups\Requests\CreateControlGroupRequest;
use Checkout\Issuing\ControlGroups\Requests\ControlGroupQuery;
use Checkout\Issuing\ControlProfiles\Requests\CreateControlProfileRequest;
use Checkout\Issuing\ControlProfiles\Requests\UpdateControlProfileRequest;
use Checkout\Issuing\ControlProfiles\Requests\ControlProfileQuery;
use Checkout\Issuing\Disputes\Requests\CreateDisputeRequest;
use Checkout\Issuing\Disputes\Requests\EscalateDisputeRequest;
use Checkout\Issuing\Disputes\Requests\SubmitDisputeRequest;
use Checkout\Issuing\Transactions\Requests\TransactionsQuery;

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
    const DISPUTES_PATH = "disputes";
    const CANCEL_PATH = "cancel";
    const ESCALATE_PATH = "escalate";
    const SUBMIT_PATH = "submit";
    const TRANSACTIONS_PATH = "transactions";
    const RENEW_PATH = "renew";
    const SCHEDULE_REVOCATION_PATH = "schedule-revocation";
    const REFUNDS_PATH = "refunds";
    const ACCESS_PATH = "access";
    const CONNECT_PATH = "connect";
    const TOKEN_PATH = "token";
    const DIGITAL_CARDS_PATH = "digital-cards";
    const OOB_PATH = "oob";
    const AUTHENTICATION_PATH = "authentication";

    public function __construct(ApiClient $apiClient, CheckoutConfiguration $configuration)
    {
        parent::__construct($apiClient, $configuration, AuthorizationType::$secretKeyOrOAuth);
    }

    /**
     * @param CardholderRequest $cardholderRequest
     * @return array
     * @throws CheckoutApiException
     */
    public function createCardholder(CardholderRequest $cardholderRequest) : array
    {
        return $this->apiClient->post(
            $this->buildPath(self::ISSUING_PATH, self::CARDHOLDERS_PATH),
            $cardholderRequest,
            $this->sdkAuthorization()
        );
    }

    /**
     * @param string $cardholderId
     * @return array
     * @throws CheckoutApiException
     */
    public function getCardholder(string $cardholderId) : array
    {
        return $this->apiClient->get(
            $this->buildPath(self::ISSUING_PATH, self::CARDHOLDERS_PATH, $cardholderId),
            $this->sdkAuthorization()
        );
    }

    /**
     * @param string $cardholderId
     * @return array
     * @throws CheckoutApiException
     */
    public function getCardholderCards(string $cardholderId) : array
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
    public function createCard(CardRequest $cardRequest) : array
    {
        return $this->apiClient->post(
            $this->buildPath(self::ISSUING_PATH, self::CARDS_PATH),
            $cardRequest,
            $this->sdkAuthorization()
        );
    }

    /**
     * @param string $cardId
     * @return array
     * @throws CheckoutApiException
     */
    public function getCardDetails(string $cardId) : array
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
    public function enrollThreeDS(string $cardId, ThreeDSEnrollmentRequest $threeDSEnrollmentRequest) : array
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
    public function updateThreeDSEnrollment(string $cardId, UpdateThreeDSEnrollmentRequest $threeDSEnrollmentRequest) : array
    {
        return $this->apiClient->patch(
            $this->buildPath(self::ISSUING_PATH, self::CARDS_PATH, $cardId, self::THREE_DS_PATH),
            $threeDSEnrollmentRequest,
            $this->sdkAuthorization()
        );
    }

    /**
     * @param string $cardId
     * @return array
     * @throws CheckoutApiException
     */
    public function getCardThreeDSDetails(string $cardId) : array
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
    public function activateCard(string $cardId) : array
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
    public function getCardCredentials(string $cardId, CardCredentialsQuery $query) : array
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
    public function revokeCard(string $cardId, RevokeCardRequest $revokeCardRequest) : array
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
    public function suspendCard(string $cardId, SuspendCardRequest $suspendCardRequest) : array
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
    public function createControl(CardControlRequest $cardControlRequest) : array
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
    public function getCardControls(CardControlsQuery $query) : array
    {
        return $this->apiClient->query(
            $this->buildPath(self::ISSUING_PATH, self::CONTROLS_PATH),
            $query,
            $this->sdkAuthorization()
        );
    }

    /**
     * @param string $controlId
     * @return array
     * @throws CheckoutApiException
     */
    public function getCardControlDetails(string $controlId) : array
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
    public function updateCardControl(string $controlId, UpdateCardControlRequest $updateCardControlRequest) : array
    {
        return $this->apiClient->put(
            $this->buildPath(self::ISSUING_PATH, self::CONTROLS_PATH, $controlId),
            $updateCardControlRequest,
            $this->sdkAuthorization()
        );
    }

    /**
     * @param string $controlId
     * @return array
     * @throws CheckoutApiException
     */
    public function removeCardControl(string $controlId) : array
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
    public function simulateAuthorization(CardAuthorizationRequest $authorizationRequest) : array
    {
        return $this->apiClient->post(
            $this->buildPath(self::ISSUING_PATH, self::SIMULATE_PATH, self::AUTHORIZATIONS_PATH),
            $authorizationRequest,
            $this->sdkAuthorization()
        );
    }

    /**
     * @param string $authorizationId
     * @param CardIncrementAuthorizationRequest $cardIncrementAuthorizationRequest
     * @return array
     * @throws CheckoutApiException
     */
    public function simulateIncrementingAuthorization(
        string $authorizationId,
        CardIncrementAuthorizationRequest $cardIncrementAuthorizationRequest
    ) : array {
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
     * @param string $authorizationId
     * @param CardClearingAuthorizationRequest $cardClearingAuthorizationRequest
     * @return array
     * @throws CheckoutApiException
     */
    public function simulateClearing(
        string $authorizationId,
        CardClearingAuthorizationRequest $cardClearingAuthorizationRequest
    ) : array {
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
     * @param string $authorizationId
     * @param CardReversalAuthorizationRequest $cardReversalAuthorizationRequest
     * @return array
     * @throws CheckoutApiException
     */
    public function simulateReversal(
        string $authorizationId,
        CardReversalAuthorizationRequest $cardReversalAuthorizationRequest
    ) : array {
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
    public function createControlGroup(CreateControlGroupRequest $createControlGroupRequest) : array
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
    public function getControlGroups(ControlGroupQuery $query) : array
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
     * @param string $controlGroupId
     * @return array
     * @throws CheckoutApiException
     */
    public function getControlGroupDetails(string $controlGroupId) : array
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
     * @param string $controlGroupId
     * @return array
     * @throws CheckoutApiException
     */
    public function removeControlGroup(string $controlGroupId) : array
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
    public function createControlProfile(CreateControlProfileRequest $createControlProfileRequest) : array
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
    public function getControlProfiles(?ControlProfileQuery $query = null) : array
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
     * @param string $controlProfileId
     * @return array
     * @throws CheckoutApiException
     */
    public function getControlProfileDetails(string $controlProfileId) : array
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
    public function updateControlProfile(string $controlProfileId, UpdateControlProfileRequest $updateControlProfileRequest) : array
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
    public function removeControlProfile(string $controlProfileId) : array
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
    public function addTargetToControlProfile(string $controlProfileId, string $targetId) : array
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
    public function removeTargetFromControlProfile(string $controlProfileId, string $targetId) : array
    {
        return $this->apiClient->post(
            $this->buildPath(self::ISSUING_PATH, self::CONTROLS_PATH, self::CONTROL_PROFILES_PATH, $controlProfileId, self::REMOVE_PATH, $targetId),
            null,
            $this->sdkAuthorization()
        );
    }

    /**
     * Create an Issuing dispute
     *
     * Create a dispute for an Issuing transaction. For full guidance, see Manage Issuing disputes.
     * The transaction must already be cleared and not refunded.
     * For the card scheme to process the chargeback, you must submit the dispute using either this endpoint,
     * or the Submit an Issuing dispute endpoint.
     *
     * @param string $idempotencyKey (Required)
     * @param CreateDisputeRequest $createDisputeRequest (Required)
     * @return array
     * @throws CheckoutApiException
     */
    public function createDispute(string $idempotencyKey, CreateDisputeRequest $createDisputeRequest) : array
    {
        return $this->apiClient->post(
            $this->buildPath(self::ISSUING_PATH, self::DISPUTES_PATH),
            $createDisputeRequest,
            $this->sdkAuthorization(),
            $idempotencyKey
        );
    }

    /**
     * Get an Issuing dispute
     *
     * Retrieve the details of an Issuing dispute.
     *
     * @param string $disputeId (Required)
     * @return array
     * @throws CheckoutApiException
     */
    public function getDispute(string $disputeId) : array
    {
        return $this->apiClient->get(
            $this->buildPath(self::ISSUING_PATH, self::DISPUTES_PATH, $disputeId),
            $this->sdkAuthorization()
        );
    }

    /**
     * Cancel an Issuing dispute
     *
     * Cancel an Issuing dispute.
     * If you decide not to proceed with a dispute, you can cancel it either:
     * - Before you submit it
     * - While the dispute status is processing and status_reason is chargeback_pending or chargeback_processed
     * For more information, see Cancel a dispute.
     *
     * @param string $disputeId (Required)
     * @return array
     * @throws CheckoutApiException
     */
    public function cancelDispute(string $disputeId) : array
    {
        return $this->apiClient->post(
            $this->buildPath(self::ISSUING_PATH, self::DISPUTES_PATH, $disputeId, self::CANCEL_PATH),
            null,
            $this->sdkAuthorization()
        );
    }

    /**
     * Escalate an Issuing dispute
     *
     * Escalate an Issuing dispute to pre-arbitration or arbitration.
     *
     * @param string $disputeId (Required)
     * @param string $idempotencyKey (Required)
     * @param EscalateDisputeRequest $escalateDisputeRequest (Required)
     * @return array
     * @throws CheckoutApiException
     */
    public function escalateDispute(string $disputeId, string $idempotencyKey, EscalateDisputeRequest $escalateDisputeRequest) : array
    {
        return $this->apiClient->post(
            $this->buildPath(self::ISSUING_PATH, self::DISPUTES_PATH, $disputeId, self::ESCALATE_PATH),
            $escalateDisputeRequest,
            $this->sdkAuthorization(),
            $idempotencyKey
        );
    }

    /**
     * Submit an Issuing dispute
     *
     * Submit an Issuing dispute to the card scheme for processing.
     *
     * @param string $disputeId (Required)
     * @param string $idempotencyKey (Required)
     * @param SubmitDisputeRequest $submitDisputeRequest (Required)
     * @return array
     * @throws CheckoutApiException
     */
    public function submitDispute(string $disputeId, string $idempotencyKey, SubmitDisputeRequest $submitDisputeRequest) : array
    {
        return $this->apiClient->post(
            $this->buildPath(self::ISSUING_PATH, self::DISPUTES_PATH, $disputeId, self::SUBMIT_PATH),
            $submitDisputeRequest,
            $this->sdkAuthorization(),
            $idempotencyKey
        );
    }

    /**
     * Get transactions
     *
     * Retrieve a list of issuing transactions. You can filter the results using optional query parameters.
     *
     * @param TransactionsQuery $query
     * @return array
     * @throws CheckoutApiException
     */
    public function getListTransactions(?TransactionsQuery $query = null) : array
    {
        return $this->apiClient->query(
            $this->buildPath(self::ISSUING_PATH, self::TRANSACTIONS_PATH),
            $query,
            $this->sdkAuthorization()
        );
    }

    /**
     * Get transaction details
     *
     * Retrieve the details of a specific issuing transaction.
     *
     * @param string $transactionId (Required)
     * @return array
     * @throws CheckoutApiException
     */
    public function getSingleTransaction(string $transactionId) : array
    {
        return $this->apiClient->get(
            $this->buildPath(self::ISSUING_PATH, self::TRANSACTIONS_PATH, $transactionId),
            $this->sdkAuthorization()
        );
    }

    /**
     * Update card details
     *
     * Update the details of an issued card.
     * Only the fields for which you provide values will be updated.
     *
     * @param string $cardId - The card's unique identifier. (Required)
     * @param UpdateCardRequest $updateCardRequest (Required)
     * @return array
     * @throws CheckoutApiException
     */
    public function updateCardDetails(string $cardId, UpdateCardRequest $updateCardRequest) : array
    {
        return $this->apiClient->patch(
            $this->buildPath(self::ISSUING_PATH, self::CARDS_PATH, $cardId),
            $updateCardRequest,
            $this->sdkAuthorization()
        );
    }

    /**
     * Renew a card
     *
     * Renew an active, inactive, or suspended card. A card cannot be renewed if it is revoked, expired, or is a single use virtual card.
     * The renewed card will have a different, nonconsecutive number (PAN), expiry date, and CVV.
     *
     * @param string $cardId - The card's unique identifier. (Required)
     * @param RenewCardRequest $renewCardRequest (Required)
     * @return array
     * @throws CheckoutApiException
     */
    public function renewCard(string $cardId, RenewCardRequest $renewCardRequest) : array
    {
        return $this->apiClient->post(
            $this->buildPath(self::ISSUING_PATH, self::CARDS_PATH, $cardId, self::RENEW_PATH),
            $renewCardRequest,
            $this->sdkAuthorization()
        );
    }

    /**
     * Schedule card revocation
     *
     * Schedules a card to be revoked at 00:00(UTC) on a specified date.
     *
     * @param string $cardId - The card's unique identifier.(Required)
     * @param ScheduleRevocationRequest $scheduleRevocationRequest (Required)
     * @return array
     * @throws CheckoutApiException
     */
    public function scheduleCardRevocation(string $cardId, ScheduleRevocationRequest $scheduleRevocationRequest) : array
    {
        return $this->apiClient->post(
            $this->buildPath(self::ISSUING_PATH, self::CARDS_PATH, $cardId, self::SCHEDULE_REVOCATION_PATH),
            $scheduleRevocationRequest,
            $this->sdkAuthorization()
        );
    }

    /**
     * Delete scheduled revocation
     *
     * Delete a card's scheduled revocation.
     *
     * @param string $cardId - The card's unique identifier. (Required)
     * @return array
     * @throws CheckoutApiException
     */
    public function deleteScheduledCardRevocation(string $cardId) : array
    {
        return $this->apiClient->delete(
            $this->buildPath(self::ISSUING_PATH, self::CARDS_PATH, $cardId, self::SCHEDULE_REVOCATION_PATH),
            $this->sdkAuthorization()
        );
    }

    /**
     * Simulate refund
     *
     * Simulate the refund of an existing approved authorization, after it has been cleared.
     *
     * @param string $authorizationId - The transaction's unique identifier. (Required)
     * @param SimulateRefundRequest $simulateRefundRequest
     * @return array
     * @throws CheckoutApiException
     */
    public function simulateRefund(string $authorizationId, SimulateRefundRequest $simulateRefundRequest) : array
    {
        return $this->apiClient->post(
            $this->buildPath(self::ISSUING_PATH, self::SIMULATE_PATH, self::AUTHORIZATIONS_PATH, $authorizationId, self::REFUNDS_PATH),
            $simulateRefundRequest,
            $this->sdkAuthorization()
        );
    }

    /**
     * Retrieves the details for a digital card (GET /issuing/digital-cards/{digitalCardId}).
     *
     * @param string $digitalCardId The digital card's unique identifier. [Required]
     * @return array
     * @throws CheckoutApiException
     */
    public function getDigitalCard(string $digitalCardId) : array
    {
        return $this->apiClient->get(
            $this->buildPath(self::ISSUING_PATH, self::DIGITAL_CARDS_PATH, $digitalCardId),
            $this->sdkAuthorization()
        );
    }

    /**
     * Simulate a request to your back-end from an out-of-band (OOB) authentication provider
     * (POST /issuing/simulate/oob/authentication).
     *
     * @param SimulateOobAuthenticationRequest $request [Required]
     * @return array
     * @throws CheckoutApiException
     */
    public function simulateOobAuthentication(SimulateOobAuthenticationRequest $request)
    {
        return $this->apiClient->post(
            $this->buildPath(
                self::ISSUING_PATH,
                self::SIMULATE_PATH,
                self::OOB_PATH,
                self::AUTHENTICATION_PATH
            ),
            $request,
            $this->sdkAuthorization()
        );
    }

    /**
     * Request an access token
     *
     * OAuth endpoint to exchange your access key ID and access key secret for an access token.
     *
     * @param CardholderAccessTokenRequest $cardholderAccessTokenRequest
     * @return array
     * @throws CheckoutApiException
     */
    public function requestCardholderAccessToken(CardholderAccessTokenRequest $cardholderAccessTokenRequest) : array
    {
        $formData = $this->createFormUrlEncodedContent($cardholderAccessTokenRequest);
        
        return $this->apiClient->post(
            $this->buildPath(self::ISSUING_PATH, self::ACCESS_PATH, self::CONNECT_PATH, self::TOKEN_PATH),
            $formData,
            $this->sdkAuthorization()
        );
    }

    /**
     * Update a cardholder
     *
     * Updates the details of an existing cardholder.
     *
     * @param string $cardholderId - The cardholder's unique identifier. (Required)
     * @param UpdateCardholderRequest $updateCardholderRequest
     * @return array
     * @throws CheckoutApiException
     */
    public function updateCardholder(string $cardholderId, UpdateCardholderRequest $updateCardholderRequest) : array
    {
        return $this->apiClient->patch(
            $this->buildPath(self::ISSUING_PATH, self::CARDHOLDERS_PATH, $cardholderId),
            $updateCardholderRequest,
            $this->sdkAuthorization()
        );
    }
}
