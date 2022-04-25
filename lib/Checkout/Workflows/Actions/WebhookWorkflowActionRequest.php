<?php

namespace Checkout\Workflows\Actions;

class WebhookWorkflowActionRequest extends WorkflowActionRequest
{
    /**
     * @var string
     */
    public $url;

    /**
     * @var array
     */
    public $headers;

    /**
     * @var WebhookSignature
     */
    public $signature;

    public function __construct()
    {
        parent::__construct(WorkflowActionType::$webhook);
    }
}
