<?php

namespace Checkout;

final class Environment
{
    private $baseUri;
    private $authorizationUri;
    private $filesBaseUri;
    private $transfersUri;
    private $balancesUri;
    private $isSandbox;

    /**
     * @param string $baseUri
     * @param string $authorizationUri
     * @param string $filesBaseUrl
     * @param string $transfersUri
     * @param string $balancesUri
     * @param bool $isSandbox
     */
    private function __construct(
        $baseUri,
        $authorizationUri,
        $filesBaseUrl,
        $transfersUri,
        $balancesUri,
        $isSandbox
    ) {
        $this->baseUri = $baseUri;
        $this->authorizationUri = $authorizationUri;
        $this->filesBaseUri = $filesBaseUrl;
        $this->transfersUri = $transfersUri;
        $this->balancesUri = $balancesUri;
        $this->isSandbox = $isSandbox;
    }

    /**
     * @return Environment
     */
    public static function sandbox()
    {
        return new Environment(
            "https://api.sandbox.checkout.com/",
            "https://access.sandbox.checkout.com/connect/token",
            "https://files.sandbox.checkout.com/",
            "https://transfers.sandbox.checkout.com/",
            "https://balances.sandbox.checkout.com/",
            true
        );
    }

    /**
     * @return Environment
     */
    public static function production()
    {

        return new Environment(
            "https://api.checkout.com/",
            "https://access.checkout.com/connect/token",
            "https://files.checkout.com/",
            "https://transfers.checkout.com/",
            "https://balances.checkout.com/",
            false
        );
    }

    /**
     * @return string
     */
    public function getBaseUri()
    {
        return $this->baseUri;
    }

    /**
     * @return string
     */
    public function getAuthorizationUri()
    {
        return $this->authorizationUri;
    }

    /**
     * @return string
     */
    public function getFilesBaseUri()
    {
        return $this->filesBaseUri;
    }

    /**
     * @return string
     */
    public function getTransfersUri()
    {
        return $this->transfersUri;
    }

    /**
     * @return bool
     */
    public function isSandbox()
    {
        return $this->isSandbox;
    }

    /**
     * @return string
     */
    public function getBalancesUri()
    {
        return $this->balancesUri;
    }
}
