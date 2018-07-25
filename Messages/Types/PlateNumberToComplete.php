<?php

namespace  Services\Messages\Types;

use  Services\Messages\MessageHelper;
use  Services\Messages\MessagesType;

class PlateNumberToComplete extends MessageHelper
{
    public function __construct(array $data)
    {
        $this->user['id'] = $data['user_id'];
        $this->status = self::ACTIVE;
        $this->title = __('Missing_plate_numbers', 0);
        $this->receiversGroups = [\Users_groups_db::LEADER, \Users_groups_db::ADMIN];
        $this->type = MessagesType::PLATE_NUMBER_TO_COMPLETE;
        $this->urlLabel = __('Go_to_list', 0);
        $this->section = 'Alerts';
        $this->pageId = $data['circle_id'];
    }

    public function prepareMessage()
    {
        $this->prepareContent();
        $this->setPageId($this->order['id']);
        $this->prepareUrl();
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
        $this->url = sprintf('calculations/index/?calcNum=%s', $this->order['number']);
    }
}
