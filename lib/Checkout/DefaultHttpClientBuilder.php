<?php

namespace Checkout;

use GuzzleHttp\Client as GuzzleHttpClient;

final class DefaultHttpClientBuilder implements HttpClientBuilderInterface
{

    private $client;

    public function __construct($config)
    {
        $this->client = new GuzzleHttpClient($config);
    }

    /**
     * @return GuzzleHttpClient
     */
    public function getClient()
    {
        return $this->client;
    }
}
