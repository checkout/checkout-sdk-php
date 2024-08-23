<?php

namespace Checkout\Sessions;

final class Optimization
{
    /**
     * @var bool
     */
    public $optimized;

    /**
     * @var string
     */
    public $framework;

    /**
     * @var array of OptimizedProperties
     */
    public $optimized_properties;
}
