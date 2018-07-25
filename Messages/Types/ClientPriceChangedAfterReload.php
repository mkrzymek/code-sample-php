<?php

namespace  Services\Messages\Types;

use  Services\Messages\MessageHelper;
use  Services\Messages\MessagesType;

class ClientPriceChangedAfterReload extends MessageHelper
{
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
        $this->user['id'] = $data['user_id'];
        $this->status = self::ACTIVE_WITH_CONFIRM_BUTTON;
        $this->title = __('Client_price_changed', 0);
        $this->receiversGroups = [\Users_groups_db::ADMIN, \Users_groups_db::LEADER];
        $this->type = MessagesType::CLIENT_PRICE_CHANGED;
        $this->urlLabel = __('Go_to_order', 0);
        $this->section = 'ClientPrice';
    }

    public function prepareMessage()
    {
        $this->prepareContent();
        $this->prepareUrl();
        $this->setPageId($this->order['id']);
    }

    private function prepareContent()
    {
        $replacements = [
            '{orderTypeName}' => $this->order['type_name'],
            '{orderNumber}' => $this->order['number'],
            '{lastName}' => $this->user['last_name'],
            '{firstName}' => $this->user['first_name'],
            '{team_name}' => $this->user['team_name'],
            '{oldPrice}' => $this->data[0],
            '{newPrice}' => $this->data[1],
        ];

        $this->content = str_replace(
            array_keys($replacements),
            array_values($replacements),
            '{orderTypeName} {orderNumber} : {lastName} {firstName} : {team_name} : {oldPrice} -> {newPrice}'
        );
    }

    private function prepareUrl()
    {
        $this->url = sprintf('orders/edit/%s/viewOnly/', $this->order['id']);
    }
}
