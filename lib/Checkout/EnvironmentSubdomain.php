<?php

namespace Checkout;

final class EnvironmentSubdomain
{
    private $baseUri;
    private $authorizationUri;

    /**
     * @param Environment $environment
     * @param $subdomain
     * @throws CheckoutArgumentException
     */
    public function __construct(Environment $environment, $subdomain)
    {
        $this->baseUri = $this->createUrlWithSubdomain($environment->getBaseUri(), $subdomain);
        $this->authorizationUri = $this->createUrlWithSubdomain($environment->getAuthorizationUri(), $subdomain);
    }

    /**
     * Applies subdomain transformation to any given URL, prepending the subdomain to the host.
     *
     * @param string $originalUrl the original URL to transform
     * @param string $subdomain the subdomain to prepend
     * @return string the transformed URL with subdomain
     * @throws CheckoutArgumentException if the subdomain is not a valid merchant-specific subdomain
     */
    private function createUrlWithSubdomain($originalUrl, $subdomain)
    {
        $regex = '/^(?:pl-)?[a-z0-9]+$/';
        if ($subdomain === null || !preg_match($regex, $subdomain)) {
            throw new CheckoutArgumentException(
                "invalid environment subdomain - provide your merchant-specific subdomain, the first 8 " .
                "characters of your client ID (see https://api-reference.checkout.com/#section/Base-URLs)"
            );
        }

        $urlParts = parse_url($originalUrl);
        $newHost = $subdomain . '.' . $urlParts['host'];

        $newUrl = $urlParts['scheme'] . '://' . $newHost;
        if (isset($urlParts['port'])) {
            $newUrl .= ':' . $urlParts['port'];
        }
        if (isset($urlParts['path'])) {
            $newUrl .= $urlParts['path'];
        }

        return $newUrl;
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
