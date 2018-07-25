<?php

namespace  Services\Messages;

interface MessageCreator
{
    public function setUserId($userId);

    public function setUser($userId);

    public function setOrder($order);

    public function setPageId($pageId);

    public function prepareMessage();

    public function getReceiversGroups();

    public function getUser();

    public function getReceiver();

    public function getContent();

    public function getTitle();

    public function getUrl();

    public function getUrlLabel();

    public function getType();

    public function getSection();

    public function getPageId();

    public function getStatus();
}
