<?php

namespace  Services\Messages\Types;

use  Services\Messages\MessageHelper;
use  Services\Messages\MessagesType;

class PlateNumberToCompleteUser extends MessageHelper
{
    public function __construct(array $data)
    {
        $this->user['id'] = $data['user_id'];
        $this->status = self::ACTIVE;
        $this->title = __('Missing_plate_numbers', 0);
        $this->receiversGroups = [];
        $this->receiverId = $data['user_id'];
        $this->type = MessagesType::PLATE_NUMBER_TO_COMPLETE_USER;
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
        ];

        $this->content = str_replace(
            array_keys($replacements),
            array_values($replacements),
            '{orderTypeName} {orderNumber}'
        );
    }

    private function prepareUrl()
    {
        $this->url = sprintf('calculations/index/?calcNum=%s', $this->order['number']);
    }
}
