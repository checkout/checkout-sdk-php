<?php

namespace Checkout\Common;

final class AccountPasswordChangeIndicatorType
{
    public static $lessThanThirtyDays = "less_than_thirty_days";
    public static $moreThanSixtyDays = "more_than_sixty_days";
    public static $noChange = "no_change";
    public static $thirtyToSixtyDays = "thirty_to_sixty_days";
    public static $thisTransaction = "this_transaction";
}
