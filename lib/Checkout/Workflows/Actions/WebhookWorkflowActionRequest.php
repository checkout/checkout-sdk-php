<?php

namespace Checkout\Workflows\Actions;

class WebhookWorkflowActionRequest extends WorkflowActionRequest
{
    public $url;

    public $headers;

    public $signature;

    public function __construct()
    {
        parent::__construct(WorkflowActionType::$webhook);
    }
}
