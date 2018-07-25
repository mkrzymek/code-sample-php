<?php

namespace  Services\Messages\Types;

use  Services\Messages\MessageHelper;
use  Services\Messages\MessagesType;

class OrderCancellation extends MessageHelper
{
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
        $this->user['id'] = $data['user_id'];
        $this->status = self::ACTIVE;
        $this->title = __('Orders_cancelation_title', 0);
        $this->receiversGroups = [\Users_groups_db::LEADER, \Users_groups_db::ADMIN];
        $this->type = MessagesType::ORDER_CANCELLATION;
        $this->urlLabel = __('Go_to_form', 0);
        $this->section = 'Alerts';
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
        ];

        $this->content = str_replace(
            array_keys($replacements),
            array_values($replacements),
            '{orderTypeName} {orderNumber} : {lastName} {firstName} : {team_name}'
        );
    }

    private function prepareUrl()
    {
        $this->url = sprintf('orders_cancellation_acceptance/create/%s', $this->data['cancellation_id']);
    }
}
