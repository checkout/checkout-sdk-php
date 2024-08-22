<?php

namespace Checkout\Sessions\Channel;

final class RequestType
{
    public static $account_verification = "account_verification";
    public static $add_card = "add_card";
    public static $installment_transaction = "installment_transaction";
    public static $mail_order = "mail_order";
    public static $maintain_card_information = "maintain_card_information";
    public static $other_payment = "other_payment";
    public static $recurring_transaction = "recurring_transaction";
    public static $split_or_delayed_shipment = "split_or_delayed_shipment";
    public static $telephone_order = "telephone_order";
    public static $top_up = "top_up";
    public static $whitelist_status_check = "whitelist_status_check";
}
