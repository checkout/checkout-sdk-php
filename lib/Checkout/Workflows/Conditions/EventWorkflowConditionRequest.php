<?php

namespace Checkout\Workflows\Conditions;

class EventWorkflowConditionRequest extends WorkflowConditionRequest
{
    /**
     * @var array
     */
    public $events;

    public function __construct()
    {
        parent::__construct(WorkflowConditionType::$event);
    }
}
