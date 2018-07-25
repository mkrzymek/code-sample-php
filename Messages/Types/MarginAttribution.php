<?php

namespace  Services\Messages\Types;

use  Services\Messages\MessageHelper;
use  Services\Messages\MessagesType;

class MarginAttribution extends MessageHelper
{
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
        $this->user['id'] = $data['user_id'];
        $this->status = self::ACTIVE;
        $this->title = __('Margin_attribution_title', 0);
        $this->receiversGroups = [\Users_groups_db::LEADER, \Users_groups_db::ADMIN];
        $this->type = MessagesType::MARGIN_ATTRIBUTION;
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
            '{teamName}' => $this->user['team_name'],
            '{fromLastName}' => $this->user['last_name'],
            '{fromFirstName}' => $this->user['first_name'],
            '{toLastName}' => $this->data[1]['last_name'],
            '{toFirstName}' => $this->data[1]['first_name'],
        ];

        $this->content = str_replace(
            array_keys($replacements),
            array_values($replacements),
            '{orderTypeName} {orderNumber} : {teamName} : {fromLastName} {fromFirstName} -> {toLastName} {toFirstName}'
        );
    }

    private function prepareUrl()
    {
        $this->url = sprintf('margin_attribution_acceptance/create/%s', $this->data[0]);
    }
}
