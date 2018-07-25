<?php

namespace  Services\Messages\Types;

use  Services\Messages\MessageHelper;
use  Services\Messages\MessagesType;

class NotCompletedUserOrder extends MessageHelper
{
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
        $this->user['id'] = $data['user_id'];
        $this->status = self::ACTIVE;
        $this->title = __('Fill_reload', 0);
        $this->receiversGroups = [];
        $this->receiverId = $data['user_id'];
        $this->type = MessagesType::NOT_COMPLETED_USER_ORDER;
        $this->urlLabel = __('Go_to_order', 0);
        $this->section = 'TruckReload';
        $this->pageId = $data['order_id'];
    }

    public function prepareMessage()
    {
        $this->prepareContent();
        $this->prepareUrl();
    }

    private function prepareContent()
    {
        $replacements = [
            '{order_type}' => $this->data['order_type'],
            '{number}' => $this->data['number'],
        ];

        $this->content = str_replace(
            array_keys($replacements),
            array_values($replacements),
            '{order_type} {number}'
        );
    }

    private function prepareUrl()
    {
        $this->url = sprintf('planning/schedule/index/?orderId=%s', $this->data['order_id']);
    }
}
