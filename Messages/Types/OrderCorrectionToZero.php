<?php

namespace  Services\Messages\Types;

use  Services\Messages\MessageHelper;
use  Services\Messages\MessagesType;

class OrderCorrectionToZero extends MessageHelper
{
    public function __construct(array $data)
    {
        $this->user['id'] = $data['user_id'];
        $this->status = self::ACTIVE;
        $this->title = __('Invoice_required', 0);
        $this->receiversGroups = [\Users_groups_db::CONTROLLER, \Users_groups_db::ADMIN];
        $this->type = MessagesType::ORDER_CORRECTION_TO_ZERO;
        $this->urlLabel = __('Invoice_gen_correction_to_zero', 0);
        $this->section = 'Notice';
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
            '{content}' => __('Orders_correction_2_zero', 0),
            '{orderTypeName}' => $this->order['type_name'],
            '{orderNumber}' => $this->order['number'],
        ];

        $this->content = str_replace(
            array_keys($replacements),
            array_values($replacements),
            '{content} : {orderTypeName} {orderNumber}'
        );
    }

    private function prepareUrl()
    {
        $this->url = sprintf('orders/create_fv_correction_2_zero/%s', $this->order['id']);
    }
}
