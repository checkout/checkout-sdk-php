<?php

namespace Checkout\StandaloneAccountUpdater\Requests;

use Checkout\StandaloneAccountUpdater\Entities\SourceOptions;

class GetUpdatedCardCredentialsRequest
{
    /**
     * The source to update. You must provide either card or instrument object, but not both. (Required)
     *
     * @var SourceOptions
     */
    public $source_options;
}
