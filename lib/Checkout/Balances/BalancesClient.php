<?php

namespace Checkout\Balances;

use Checkout\ApiClient;
use Checkout\AuthorizationType;
use Checkout\CheckoutApiException;
use Checkout\CheckoutConfiguration;
use Checkout\Client;

class BalancesClient extends Client
{
    const BALANCES_PATH = "balances";
    const ENTITIES_PATH = "entities";
    const CURRENCY_ACCOUNTS_PATH = "currency-accounts";
    const TOP_UP_INSTRUCTIONS_PATH = "top-up-instructions";

    public function __construct(ApiClient $apiClient, CheckoutConfiguration $configuration)
    {
        parent::__construct($apiClient, $configuration, AuthorizationType::$secretKeyOrOAuth);
    }

    /**
     * Retrieves the balances for each sub-account belonging to an entity.
     *
     * @param $entityId
     * @param BalancesQuery $balancesQuery
     * @return array
     * @throws CheckoutApiException
     */
    public function retrieveEntityBalances($entityId, BalancesQuery $balancesQuery)
    {
        return $this->apiClient->query(
            $this->buildPath(self::BALANCES_PATH, $entityId),
            $balancesQuery,
            $this->sdkAuthorization()
        );
    }

    /**
     * Retrieves the bank details required to top up a sub-account, along with the payment
     * reference that attributes an incoming payment to that sub-account.
     * Note: The sub-account is referred to as currency account in the API.
     *
     * The returned array has the following shape. This SDK returns decoded JSON, so there are
     * no response classes; the keys below are the wire names.
     *
     * - currency_account_id  string  [Required] The unique identifier of the sub-account that the
     *                                instructions apply to.
     * - currency             string  [Required] The currency that funds must be sent in, as a
     *                                three-letter ISO 4217 currency code. This is the
     *                                sub-account's holding currency, returned as
     *                                holding_currency by the Retrieve entity balances endpoint.
     * - payment_reference    string  [Required] The reference that must be quoted on the payment.
     *                                It is how an incoming payment is attributed to the
     *                                sub-account. A payment sent without this reference may not
     *                                be credited.
     * - bank_details         array   [Required] The bank details for each available funding rail:
     *   - domestic           array   [Optional] Funding details for the domestic rail.
     *   - international      array   [Optional] Funding details for the international rail.
     *
     * Both rails are optional and their availability depends on the sub-account's holding
     * currency, jurisdiction, and banking partner. Do not assume that both rails are always
     * available; bank_details may contain neither.
     *
     * Each rail, when present, has the following keys. Only beneficiary_account_name and
     * bank_name are always returned; the rest vary by rail and the receiving bank's jurisdiction,
     * and are omitted when they do not apply.
     *
     * - beneficiary_account_name  string  [Required] The name of the account that receives the funds.
     * - beneficiary_address       string  [Optional] The address of the beneficiary, if the rail requires it.
     * - bank_name                 string  [Required] The name of the bank that receives the funds.
     * - bank_address              string  [Optional] The address of the receiving bank, if the rail requires it.
     * - account_number            string  [Optional] The account number of the receiving account.
     * - sort_code                 string  [Optional] The sort code of the receiving bank. Returned for United Kingdom domestic transfers.
     * - routing_number            string  [Optional] The routing number of the receiving bank. Returned for United States domestic transfers.
     * - iban                      string  [Optional] The International Bank Account Number of the receiving account.
     * - swift_code                string  [Optional] The SWIFT or BIC code of the receiving bank. Returned for international transfers.
     *
     * @param string $entityId the ID of the entity that owns the sub-account, or of an entity
     *                         above it in your hierarchy. A platform can use its own entity ID to
     *                         reach the sub-accounts of any entity beneath it
     * @param string $currencyAccountId the ID of the sub-account to retrieve top-up instructions for
     * @return array
     * @throws CheckoutApiException
     */
    public function retrieveTopUpInstructions(string $entityId, string $currencyAccountId)
    {
        return $this->apiClient->get(
            $this->buildPath(
                self::ENTITIES_PATH,
                $entityId,
                self::CURRENCY_ACCOUNTS_PATH,
                $currencyAccountId,
                self::TOP_UP_INSTRUCTIONS_PATH
            ),
            $this->sdkAuthorization()
        );
    }
}
