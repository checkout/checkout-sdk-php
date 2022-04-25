<?php

namespace Checkout\Workflows\Conditions;

class ProcessingChannelWorkflowConditionRequest extends WorkflowConditionRequest
{
    /**
     * @var array
     */
    public $processing_channels;

    public function __construct()
    {
        parent::__construct(WorkflowConditionType::$processing_channel);
    }
}
