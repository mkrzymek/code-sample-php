<?php

namespace  Services\Messages\Types;

use  Services\Messages\MessageHelper;
use  Services\Messages\MessagesType;

class NotCompletedOrder extends MessageHelper
{
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
        $this->user['id'] = $data['user_id'];
        $this->title = __('Fill_reload', 0);
        $this->status = self::ACTIVE;
        $this->receiversGroups = [\Users_groups_db::ADMIN, \Users_groups_db::LEADER];
        $this->type = MessagesType::NOT_COMPLETED_ORDER;
        $this->section = 'TruckReload';
        $this->pageId = $data['order_id'];
    }

    public function prepareMessage()
    {
        $this->prepareContent();
    }

    private function prepareContent()
    {
        $replacements = [
            '{order_type}' => $this->data['order_type'],
            '{number}' => $this->data['number'],
            '{last_name}' => $this->data['last_name'],
            '{first_name}' => $this->data['first_name'],
            '{team_name}' => $this->user['team_name'],
        ];

        $this->content = str_replace(
            array_keys($replacements),
            array_values($replacements),
            '{order_type} {number} : {last_name} {first_name} : {team_name}'
        );
    }
}
