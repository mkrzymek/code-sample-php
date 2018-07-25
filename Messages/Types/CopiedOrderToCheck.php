<?php

namespace  Services\Messages\Types;

use  Services\Messages\MessageHelper;
use  Services\Messages\MessagesType;

class CopiedOrderToCheck extends MessageHelper
{
    public function __construct()
    {
        $this->status = self::ACTIVE;
        $this->title = __('Copied_order_to_check', 0);
        $this->receiversGroups = [
            \Users_groups_db::LEADER,
            \Users_groups_db::ADMIN
        ];
        $this->type = MessagesType::COPIED_ORDER_TO_CHECK;
        $this->urlLabel = __('Go_to_order', 0);
        $this->section = 'Alerts';
    }

    public function prepareMessage()
    {
        $this->prepareContent();
        $this->prepareUrl();
        $this->setPageId($this->order['id']);
        $this->receiverId = $this->user['id'];
    }

    private function prepareContent()
    {
        $replacements = [
            '{content1}' => __('Copied_order_to_check_content1', 0),
            '{orderTypeName}' => $this->order['type_name'],
            '{orderNumber}' => $this->order['number'],
            '{content2}' => __('Copied_order_to_check_content2', 0),
        ];

        $this->content = str_replace(
            array_keys($replacements),
            array_values($replacements),
            '{content1} {orderTypeName} {orderNumber} {content2}'
        );
    }

    private function prepareUrl()
    {
        $this->url = sprintf('orders/edit/%s', $this->order['id']);
    }
}
