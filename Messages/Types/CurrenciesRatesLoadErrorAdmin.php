<?php

namespace  Services\Messages\Types;

use  Services\Messages\MessageHelper;
use  Services\Messages\MessagesType;

class CurrenciesRatesLoadErrorAdmin extends MessageHelper
{
    public function __construct()
    {
        $this->status = self::ACTIVE;
        $this->title = __('No_cur_rates_admin_title', 0);
        $this->content = __('No_cur_rates_admin', 0);
        $this->url = 'manage/rates/';
        $this->urlLabel = __('Go_to_cur_rates', 0);
        $this->receiversGroups = [\Users_groups_db::ADMIN, \Users_groups_db::GM];
        $this->type = MessagesType::CURRENCIES_RATE_ADMIN_ERROR;
        $this->section = 'Alerts';
    }

    public function prepareMessage()
    {
    }
}
