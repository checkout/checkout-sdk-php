<?php

namespace Checkout;

final class Environment
{
    private string $baseUri;
    private string $authorizationUri;
    private string $filesBaseUri;
    private string $transfersUri;
    private bool $isSandbox;

    private function __construct(string $baseUri,
                                 string $authorizationUri,
                                 string $filesBaseUrl,
                                 string $transfersUri,
                                 ?bool  $isSandbox = true)
    {
        $this->baseUri = $baseUri;
        $this->authorizationUri = $authorizationUri;
        $this->filesBaseUri = $filesBaseUrl;
        $this->transfersUri = $transfersUri;
        $this->isSandbox = $isSandbox;
    }

    public static function sandbox(): Environment
    {
        return new Environment("https://api.sandbox.checkout.com/",
            "https://access.sandbox.checkout.com/connect/token",
            "https://files.sandbox.checkout.com/",
            "https://transfers.sandbox.checkout.com/",
            true);
    }

    public static function production(): Environment
    {

        return new Environment("https://api.checkout.com/",
            "https://access.checkout.com/connect/token",
            "https://files.checkout.com/",
            "https://transfers.checkout.com/",
            false);

    }

    public function getBaseUri(): string
    {
        return $this->baseUri;
    }

    public function getAuthorizationUri(): string
    {
        return $this->authorizationUri;
    }

    public function getFilesBaseUri(): string
    {
        return $this->filesBaseUri;
    }

    public function getTransfersUri(): string
    {
        return $this->transfersUri;
    }

    public function isSandbox(): bool
    {
        return $this->isSandbox;
    }

}
