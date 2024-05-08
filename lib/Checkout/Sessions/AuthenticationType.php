<?php

namespace Checkout\Sessions;

final class AuthenticationType
{
    public static $regular = "regular";
    public static $recurring = "recurring";
    public static $installment = "installment";
    public static $maintain_card = "maintain_card";
    public static $add_card = "add_card";
}
