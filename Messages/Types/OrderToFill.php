<?php

namespace  Services\Messages\Types;

use  Services\Messages\MessageHelper;
use  Services\Messages\MessagesType;

class OrderToFill extends MessageHelper
{
    public function __construct(array $data)
    {
        $this->user['id'] = $data['user_id'];
        $this->status = self::ACTIVE;
        $this->title = __('Fill_order', 0);
        $this->receiversGroups = [
            \Users_groups_db::ORDER_ADMINISTRATOR,
            \Users_groups_db::LEADER,
            \Users_groups_db::ADMIN
        ];
        $this->type = MessagesType::ORDER_TO_FILL;
        $this->urlLabel = __('Go_to_order', 0);
        $this->section = 'FillOrder';
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
        ];

        $this->content = str_replace(
            array_keys($replacements),
            array_values($replacements),
            '{orderTypeName} {orderNumber}'
        );
    }

    private function prepareUrl()
    {
        $this->url = sprintf('orders/edit/%s', $this->order['id']);
    }
}
