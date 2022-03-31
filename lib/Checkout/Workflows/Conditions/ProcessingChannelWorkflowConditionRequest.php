<?php

namespace Checkout\Workflows\Conditions;

class ProcessingChannelWorkflowConditionRequest extends WorkflowConditionRequest
{
    public $processing_channels;

    public function __construct()
    {
        parent::__construct(WorkflowConditionType::$processing_channel);
    }
}
