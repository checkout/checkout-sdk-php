<?php

namespace Checkout;

use GuzzleHttp\Client as GuzzleHttpClient;

final class DefaultHttpClientBuilder implements HttpClientBuilderInterface
{

    private $client;

    public function __construct()
    {
        $this->client = new GuzzleHttpClient();
    }

    /**
     * @return GuzzleHttpClient
     */
    public function getClient()
    {
        return $this->client;
    }
}
