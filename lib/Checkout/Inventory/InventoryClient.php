<?php

namespace Checkout\Inventory;

use Checkout\ApiClient;
use Checkout\AuthorizationType;
use Checkout\CheckoutApiException;
use Checkout\CheckoutConfiguration;
use Checkout\Client;
use Checkout\Inventory\Requests\InventoryAdjustmentRequest;
use Checkout\Inventory\Requests\InventoryReservationRequest;
use Checkout\Inventory\Requests\InventorySetLevelsRequest;
use Checkout\Inventory\Requests\InventorySetProductRequest;

/**
 * All Inventory operations require the OAuth `agentic:inventory` scope (no ApiSecretKey /
 * ApiPublicKey support). Configure your SDK builder's OAuth credentials with that scope.
 */
class InventoryClient extends Client
{
    const INVENTORY_PATH = "inventory";
    const ADJUSTMENTS_PATH = "adjustments";
    const RESERVATIONS_PATH = "reservations";
    const COMMIT_PATH = "commit";
    const RELEASE_PATH = "release";
    const PRODUCT_PATH = "product";

    public function __construct(ApiClient $apiClient, CheckoutConfiguration $configuration)
    {
        parent::__construct($apiClient, $configuration, AuthorizationType::$oAuth);
    }

    /**
     * Adjust inventory
     *
     * Applies a relative delta to a variant's physical stock (on_hand) with a mandatory reason
     * (for example, damaged or found stock). The call fails if the delta drives the physical
     * stock below zero. An `adjust` entry is written to the ledger.
     * Returns 201 on the initial call, or 200 (idempotent replay) with a Cache-Control response
     * header when Cko-Idempotency-Key is reused.
     *
     * @param InventoryAdjustmentRequest $inventoryAdjustmentRequest (Required)
     * @param string|null $idempotencyKey (Optional) - value of the Cko-Idempotency-Key header
     * @return array
     * @throws CheckoutApiException
     */
    public function adjustInventory(
        InventoryAdjustmentRequest $inventoryAdjustmentRequest,
        ?string $idempotencyKey = null
    ): array {
        return $this->apiClient->post(
            $this->buildPath(self::INVENTORY_PATH, self::ADJUSTMENTS_PATH),
            $inventoryAdjustmentRequest,
            $this->sdkAuthorization(),
            $idempotencyKey
        );
    }

    /**
     * Create an inventory reservation
     *
     * Creates an atomic hold across multiple variants, with a time-to-live (TTL). All items in
     * the hold are reserved together, or none are, so a reservation can never oversell. The hold
     * is protocol-neutral: it is bound to an owner_type and owner_reference supplied by the
     * calling protocol adapter, for example a UCP session or an ACP checkout.
     * Returns 201 on the initial call, or 200 (idempotent replay) with a Cache-Control response
     * header when Cko-Idempotency-Key is reused.
     *
     * @param InventoryReservationRequest $inventoryReservationRequest (Required)
     * @param string|null $idempotencyKey (Optional) - value of the Cko-Idempotency-Key header
     * @return array
     * @throws CheckoutApiException
     */
    public function createInventoryReservation(
        InventoryReservationRequest $inventoryReservationRequest,
        ?string $idempotencyKey = null
    ): array {
        return $this->apiClient->post(
            $this->buildPath(self::INVENTORY_PATH, self::RESERVATIONS_PATH),
            $inventoryReservationRequest,
            $this->sdkAuthorization(),
            $idempotencyKey
        );
    }

    /**
     * Get an inventory reservation
     *
     * Returns a reservation in any state. A reservation with a held state past its expires_at
     * reports as expired.
     *
     * @param string $reservationId (Required) - the reservation identifier
     * @return array
     * @throws CheckoutApiException
     */
    public function getInventoryReservation(string $reservationId): array
    {
        return $this->apiClient->get(
            $this->buildPath(self::INVENTORY_PATH, self::RESERVATIONS_PATH, $reservationId),
            $this->sdkAuthorization()
        );
    }

    /**
     * Commit an inventory reservation
     *
     * Converts a hold into a sale on order completion. In one atomic operation, decrements
     * on_hand for every item in the reservation and marks the reservation as committed.
     *
     * @param string $reservationId (Required) - the reservation identifier
     * @return array
     * @throws CheckoutApiException
     */
    public function commitInventoryReservation(string $reservationId): array
    {
        return $this->apiClient->post(
            $this->buildPath(self::INVENTORY_PATH, self::RESERVATIONS_PATH, $reservationId, self::COMMIT_PATH),
            null,
            $this->sdkAuthorization()
        );
    }

    /**
     * Release an inventory reservation
     *
     * Releases a hold when a checkout is cancelled or abandoned. In one atomic operation, frees
     * the held quantities and marks the reservation as released.
     *
     * @param string $reservationId (Required) - the reservation identifier
     * @return array
     * @throws CheckoutApiException
     */
    public function releaseInventoryReservation(string $reservationId): array
    {
        return $this->apiClient->post(
            $this->buildPath(self::INVENTORY_PATH, self::RESERVATIONS_PATH, $reservationId, self::RELEASE_PATH),
            null,
            $this->sdkAuthorization()
        );
    }

    /**
     * Get inventory levels
     *
     * Returns the current stock levels and sellable availability for a variant. Pass
     * `$expand = "product"` to embed product knowledge in the response, when it exists.
     *
     * @param string $variantId (Required) - the variant identifier
     * @param string|null $expand (Optional) - pass "product" to embed product knowledge
     * @return array
     * @throws CheckoutApiException
     */
    public function getInventoryLevels(string $variantId, ?string $expand = null): array
    {
        $path = $this->buildPath(self::INVENTORY_PATH, $variantId);
        if (!empty($expand)) {
            $path .= "?expand=" . urlencode($expand);
        }
        return $this->apiClient->get($path, $this->sdkAuthorization());
    }

    /**
     * Set inventory levels
     *
     * Sets the absolute stock levels for a variant (Managed mode and corrections). The call is
     * an upsert: it returns 201 when the inventory item is created and 200 when an existing item
     * is updated. Availability is recomputed and a `set` entry is written to the ledger. Active
     * reservations are never disturbed.
     *
     * @param string $variantId (Required) - the variant identifier
     * @param InventorySetLevelsRequest $inventorySetLevelsRequest (Required)
     * @return array
     * @throws CheckoutApiException
     */
    public function setInventoryLevels(
        string $variantId,
        InventorySetLevelsRequest $inventorySetLevelsRequest
    ): array {
        return $this->apiClient->put(
            $this->buildPath(self::INVENTORY_PATH, $variantId),
            $inventorySetLevelsRequest,
            $this->sdkAuthorization()
        );
    }

    /**
     * [Beta]
     * Get inventory product knowledge
     *
     * Returns the merchandising metadata ("product knowledge") for a variant, used by AI agents.
     *
     * @param string $variantId (Required) - the variant identifier
     * @return array
     * @throws CheckoutApiException
     */
    public function getInventoryProduct(string $variantId): array
    {
        return $this->apiClient->get(
            $this->buildPath(self::INVENTORY_PATH, $variantId, self::PRODUCT_PATH),
            $this->sdkAuthorization()
        );
    }

    /**
     * [Beta]
     * Set inventory product knowledge
     *
     * Sets the merchandising metadata ("product knowledge") for a variant. The call is an
     * upsert.
     *
     * @param string $variantId (Required) - the variant identifier
     * @param InventorySetProductRequest $inventorySetProductRequest (Required)
     * @return array
     * @throws CheckoutApiException
     */
    public function setInventoryProduct(
        string $variantId,
        InventorySetProductRequest $inventorySetProductRequest
    ): array {
        return $this->apiClient->put(
            $this->buildPath(self::INVENTORY_PATH, $variantId, self::PRODUCT_PATH),
            $inventorySetProductRequest,
            $this->sdkAuthorization()
        );
    }

    /**
     * [Beta]
     * Delete inventory product knowledge
     *
     * Deletes the merchandising metadata ("product knowledge") for a variant. Returns 204 with
     * no body.
     *
     * @param string $variantId (Required) - the variant identifier
     * @return array
     * @throws CheckoutApiException
     */
    public function deleteInventoryProduct(string $variantId): array
    {
        return $this->apiClient->delete(
            $this->buildPath(self::INVENTORY_PATH, $variantId, self::PRODUCT_PATH),
            $this->sdkAuthorization()
        );
    }
}
