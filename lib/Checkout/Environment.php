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

    public function getBaseUri()
    {
        return $this->baseUri;
    }

    public function getAuthorizationUri()
    {
        return $this->authorizationUri;
    }

    public function getFilesBaseUri()
    {
        return $this->filesBaseUri;
    }

    public function getTransfersUri()
    {
        return $this->transfersUri;
    }

    public function isSandbox()
    {
        return $this->isSandbox;
    }

    public function getBalancesUri()
    {
        return $this->balancesUri;
    }
}
