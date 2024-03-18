<?php

namespace Checkout;

final class EnvironmentSubdomain
{
    private $baseUri;

    /**
     * @param Environment $environment
     * @param $subdomain
     */
    public function __construct(Environment $environment, $subdomain)
    {
        $this->baseUri = $this->addSubdomainToApiUrlEnvironment($environment, $subdomain);
    }

    /**
     * @param $environment
     * @param $subdomain
     * @return string
     */
    private function addSubdomainToApiUrlEnvironment($environment, $subdomain)
    {
        $apiUrl = $environment->getBaseUri();
        $newEnvironment = $apiUrl;

        $regex = '/^[0-9a-z]{8}$/';
        if (preg_match($regex, $subdomain)) {
            $urlParts = parse_url($apiUrl);
            $newHost = $subdomain . '.' . $urlParts['host'];

            $newUrl = $urlParts['scheme'] . '://' . $newHost;
            if (isset($urlParts['port'])) {
                $newUrl .= ':' . $urlParts['port'];
            }
            if (isset($urlParts['path'])) {
                $newUrl .= $urlParts['path'];
            }

            $newEnvironment = $newUrl;
        }

        return $newEnvironment;
    }

    /**
     * @return string
     */
    public function getBaseUri()
    {
        return $this->baseUri;
    }
}
