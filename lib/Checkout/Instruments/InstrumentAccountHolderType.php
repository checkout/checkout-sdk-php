<?php

namespace Checkout\Instruments;

/**
 * The type of account holder on a stored instrument.
 *
 * The instrument schemas allow individual and corporate only. Do not use
 * Checkout\Common\AccountHolderType, which also carries government.
 */
class InstrumentAccountHolderType
{
    public static $individual = "individual";

    public static $corporate = "corporate";
}
