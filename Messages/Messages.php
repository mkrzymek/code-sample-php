<?php

namespace  Services\Messages;

use \Orders_db;
use \Users_db;
use \CI_Session;

class Messages extends MessagesType
{
    /** @var  Users_db */
    private $usersDB;
    /** @var  Orders_db */
    private $ordersDB;
    /** @var  CI_Session */
    private $session;

    private $receiversGroups;
    private $messageData;

    /**
     * @param int $type
     * @param int|null $orderId
     * @param array $data
     * @param CI_Session $session
     * @param Users_db $usersDB
     * @param Orders_db $ordersDB
     */
    public function __construct($type, $orderId, $data, CI_Session $session, Users_db $usersDB, Orders_db $ordersDB)
    {
        parent::__construct($type, $data);

        $this->usersDB = $usersDB;
        $this->ordersDB = $ordersDB;
        $this->session = $session;

        $this->prepareMessageDetails($orderId);
    }

    /**
     * @param int|null $orderId
     */
    public function prepareMessageDetails($orderId)
    {
        $this->prepareUserDetails($this->session->userdata('user_id'));
        $this->prepareOrderDetails($orderId);
        $this->prepareReceivers();

        $this->message->prepareMessage();

        $this->createMessage();
    }

    /**
     * @param int|null $userId
     */
    private function prepareUserDetails($userId)
    {
        $this->message->setUserId($userId);
        $userDetails = $this->usersDB->get($this->message->getUser()['id']) ?? [];
        $userTeam = $this->usersDB->getUserTeam($this->message->getUser()['id']) ?? [
                'team_name' => 'No Team',
                'team_id' => null
            ];

        $userDetails = array_merge($userDetails, $userTeam);

        $this->message->setUser($userDetails);
    }

    /**
     * @param int|null $orderId
     */
    private function prepareOrderDetails($orderId)
    {
        $orderDetails = $this->ordersDB->getOrderById($orderId);
        $this->message->setOrder($orderDetails);
    }

    private function prepareReceivers()
    {
        $this->receiversGroups = $this->message->getReceiversGroups();
    }

    public function createMessage()
    {
        $this->messageData = [
            'receiver_user_id' => $this->message->getReceiver(),
            'sender_team_id' => $this->message->getUser()['team_id'],
            'page_id' => $this->message->getPageId(),
            'section' => $this->message->getSection(),
            'type' => $this->message->getType(),
            'status' => $this->message->getStatus(),
            'title' => $this->message->getTitle(),
            'content' => $this->message->getContent(),
            'url' => $this->message->getUrl(),
            'url_label' => $this->message->getUrlLabel(),
        ];
    }

    public function getMassageDetails()
    {
        return $this->messageData;
    }

    public function getReceiversGroups()
    {
        return $this->receiversGroups;
    }
}
