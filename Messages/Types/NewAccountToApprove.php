<?php

namespace  Services\Messages\Types;

use  Services\Messages\MessageHelper;
use  Services\Messages\MessagesType;

class NewAccountToApprove extends MessageHelper
{
    public function __construct(array $data)
    {
        $this->user['id'] = $data['user_id'];
        $this->status = self::ACTIVE_WITH_CONFIRM_BUTTON;
        $this->title = __('Pay_account_to_approve_title', 0);
        $this->receiversGroups = [\Users_groups_db::ADMIN, \Users_groups_db::GM];
        $this->type = MessagesType::NEW_ACCOUNT_TO_APPROVE;
        $this->urlLabel = __('Go_to_form', 0);
        $this->section = 'Alerts';
        $this->pageId = $data['pageId'];
    }

    public function prepareMessage()
    {
        $this->prepareContent();
        $this->prepareUrl();
    }

    private function prepareContent()
    {
        $replacements = [
            '{teamName}' => $this->user['team_name'],
            '{LastName}' => $this->user['last_name'],
            '{FirstName}' => $this->user['first_name'],
        ];

        $this->content = str_replace(
            array_keys($replacements),
            array_values($replacements),
            '{teamName} : {LastName} {FirstName}'
        );
    }

    private function prepareUrl()
    {
        $this->url = sprintf('manage/accounts_list/%s', $this->pageId);
    }
}
