<?php

namespace Checkout;

class HttpMetadata
{
    private $reason_phrase;
    private $status_code;
    private $headers;
    private $protocol;

    /**
     * @param $reason_phrase
     * @param $status_code
     * @param $headers
     * @param $protocol
     */
    public function __construct($reason_phrase, $status_code, $headers, $protocol)
    {
        $this->reason_phrase = $reason_phrase;
        $this->status_code = $status_code;
        $this->headers = $headers;
        $this->protocol = $protocol;
    }

    /**
     * @return mixed
     */
    public function getReasonPhrase()
    {
        return $this->reason_phrase;
    }

    /**
     * @return mixed
     */
    public function getStatusCode()
    {
        return $this->status_code;
    }

    /**
     * @return mixed
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @return mixed
     */
    public function getProtocol()
    {
        return $this->protocol;
    }
}
