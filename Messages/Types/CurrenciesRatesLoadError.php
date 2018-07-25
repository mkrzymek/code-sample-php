<?php

namespace  Services\Messages\Types;

use  Services\Messages\MessageHelper;
use  Services\Messages\MessagesType;

class CurrenciesRatesLoadError extends MessageHelper
{
    public function __construct()
    {
        $this->status = self::ACTIVE;
        $this->title = __('No_cur_rates_title', 0);
        $this->content = __('No_cur_rates', 0);
        $this->url = \MessagesDb::LOCKED_TAG;
        $this->receiversGroups = [
            \Users_groups_db::FORWARDER,
            \Users_groups_db::DISPATCHER,
            \Users_groups_db::CONTROLLER
        ];
        $this->type = MessagesType::CURRENCIES_RATE_ERROR;
        $this->section = 'Alerts';
    }

    public function prepareMessage()
    {
    }
}
