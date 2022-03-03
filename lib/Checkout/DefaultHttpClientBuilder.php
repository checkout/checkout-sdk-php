<?php

namespace Checkout;

use GuzzleHttp\Client as GuzzleHttpClient;

final class DefaultHttpClientBuilder implements HttpClientBuilderInterface
{

    private GuzzleHttpClient $client;

    public function __construct()
    {
        $this->client = new GuzzleHttpClient();
    }

    public function getClient(): GuzzleHttpClient
    {
        return $this->client;
    }

}
