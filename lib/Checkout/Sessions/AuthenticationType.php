<?php

namespace Checkout\Sessions;

final class AuthenticationType
{
    public static $add_card = "add_card";
    public static $installment = "installment";
    public static $maintain_card = "maintain_card";
    public static $recurring = "recurring";
    public static $regular = "regular";
}
