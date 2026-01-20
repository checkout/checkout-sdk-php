<?php

namespace Checkout;

final class EnvironmentSubdomain
{
    private $baseUri;
    private $authorizationUri;

    /**
     * @param Environment $environment
     * @param $subdomain
     */
    public function __construct(Environment $environment, $subdomain)
    {
        $this->baseUri = $this->createUrlWithSubdomain($environment->getBaseUri(), $subdomain);
        $this->authorizationUri = $this->createUrlWithSubdomain($environment->getAuthorizationUri(), $subdomain);
    }

    /**
     * Applies subdomain transformation to any given URL.
     * If the subdomain is valid (alphanumeric pattern), prepends it to the host.
     * Otherwise, returns the original URL unchanged.
     *
     * @param string $originalUrl the original URL to transform
     * @param string $subdomain the subdomain to prepend
     * @return string the transformed URL with subdomain, or original URL if subdomain is invalid
     */
    private function createUrlWithSubdomain($originalUrl, $subdomain)
    {
        $newEnvironment = $originalUrl;

        $regex = '/^[0-9a-z]+$/';
        if (preg_match($regex, $subdomain)) {
            $urlParts = parse_url($originalUrl);
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

    /**
     * @return string
     */
    public function getAuthorizationUri()
    {
        return $this->authorizationUri;
    }
}
