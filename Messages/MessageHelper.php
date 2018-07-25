<?php

namespace  Services\Messages;

use  Services\Messages\MessageCreator;

abstract class MessageHelper implements MessageCreator
{
    protected $receiversGroups;
    protected $receiverId;
    protected $section;
    protected $type;
    protected $title;
    protected $status;
    protected $content;
    protected $url;
    protected $urlLabel;
    protected $pageId;
    protected $order;
    protected $user;

    /** statuses */
    const INACTIVE = 0;
    const ACTIVE = 1;
    const ACTIVE_WITH_CONFIRM_BUTTON = 2;
    const ACTIVE_AND_EDITABLE = 3;
    const ACTIVE_WITH_CONFIRM_BUTTON_AND_EDITABLE = 4;

    public function setUserId($userId)
    {
        $this->user['id'] = $this->user['id'] ?? $userId;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function setOrder($order)
    {
        $this->order = $order ? $order : ['id' => null];
    }

    public function setPageId($pageId)
    {
        $this->pageId = $pageId;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getReceiver()
    {
        return $this->receiverId;
    }

    public function getReceiversGroups()
    {
        return $this->receiversGroups;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function getUrlLabel()
    {
        return $this->urlLabel;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getSection()
    {
        return $this->section;
    }

    public function getPageId()
    {
        return $this->pageId;
    }

    public function getStatus()
    {
        return $this->status;
    }
}
