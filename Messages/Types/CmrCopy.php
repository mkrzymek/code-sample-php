<?php

namespace  Services\Messages\Types;

use  Services\Messages\MessageHelper;
use  Services\Messages\MessagesType;

class CmrCopy extends MessageHelper
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
        $this->user['id'] = $data['user_id'];
        $this->status = self::ACTIVE_WITH_CONFIRM_BUTTON_AND_EDITABLE;
        $this->receiversGroups = [\Users_groups_db::CONTROLLER, \Users_groups_db::ADMIN];
        $this->type = MessagesType::CMR_COPY;
        $this->content = '';
        $this->urlLabel = __('Go_to_order', 0);
        $this->section = 'FillOrder';
        $this->pageId = $data['order_id'];
    }

    public function prepareMessage()
    {
        $this->prepareTitle();
        $this->prepareUrl();
    }

    private function prepareTitle()
    {
        $replacements = [
            '{title}' => __('Cmr_fetch_cmr_copy', 0),
            '{order_type}' => $this->data['order_type'],
            '{number}' => $this->data['number']
        ];

        $this->title = str_replace(
            array_keys($replacements),
            array_values($replacements),
            '{title} : {order_type} {number}'
        );
    }

    private function prepareUrl()
    {
        $this->url = sprintf('orders/edit_document/%s', $this->order['id']);
    }
}
