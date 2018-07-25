<?php

use Exceptions\MessageNotAllowedException;
use Services\Messages\Messages;
use Services\Messages\MessageHelper;

/**
 * @property \CI_Session $session
 * @property \Users_db $users_db
 * @property \Orders_db $orders_db
 * @property \CI_DB_query_builder $db
 */
class MessagesDb extends CI_Model
{
    const INACTIVE = 0;
    const LOCKED_TAG = 'locked';

    public function getAllUserMessages(int $userId) : array
    {
        $isTeamLeader = false;
        $userGroups = $this->users_db->get_user_groups();

        foreach ($userGroups as $group) {
            if ($group == Users_groups_db::LEADER) {
                $isTeamLeader = true;
            }
        }

        $userActiveMessages = $this->db->select('m.*, ut.team_id as leader_team_id')
            ->from('popup_messages m')
            ->join('users_teams ut', 'ut.user_id = ' . $userId, 'left')
            ->group_start()
            ->where('m.receiver_user_id', $userId)
            ->or_where_in('m.receiver_group_id', $userGroups)
            ->group_end()
            ->where('m.status !=', self::INACTIVE)
            ->order_by('m.type')
            ->order_by('(m.sender_team_id = ut.team_id) desc')
            ->order_by('m.sender_team_id desc')
            ->order_by('m.title desc')
            ->group_by('page_id, content, type')
            ->get()->result_array();

        $messagesBySection = [];

        foreach ($userActiveMessages as $row) {
            $row['is_team_leader'] = $isTeamLeader;
            $messagesBySection[$row['section']][] = $row;
        }

        return $messagesBySection;
    }

    /**
     * @param int $type
     * @param int|null $orderId
     * @param array $messageParameters
     */
    public function addNewMessages($type, $orderId, $messageParameters = [])
    {
        $messages = new Messages($type, $orderId, $messageParameters, $this->session, $this->users_db,
            $this->orders_db);
        $messageDetails = $messages->getMassageDetails();
        $receiversGroups = $messages->getReceiversGroups();

        if (!empty($messageDetails['receiver_user_id'])) {
            $this->addMessage($messageDetails);
        }

        foreach ($receiversGroups as $groupId) {
            $messageParameters = array_merge(['receiver_group_id' => $groupId], $messageDetails);
            $this->addMessage($messageParameters);
        }
    }

    private function addMessage(array $messageParameters)
    {
        $messageExists = $this->findMessagesBy($messageParameters);

        if (!$messageExists) {
            $this->db->insert('popup_messages', $messageParameters);
        } elseif ($messageExists['status'] == self::INACTIVE) {
            $this->db->where('receiver_user_id', $messageParameters['receiver_user_id'])
                ->where('receiver_group_id', $messageParameters['receiver_group_id'])
                ->where('page_id', $messageParameters['page_id'])
                ->where('content', $messageParameters['content'])
                ->update('popup_messages', ['status' => $messageParameters['status']]);
        }
    }

    /**
     * @param int $type
     * @param int|null $pageId
     */
    public function markMessagesAsInactive($type, $pageId)
    {
        $userId = $this->session->userdata('user_id');

        $this->db->where('page_id', $pageId)
            ->where('type', $type)
            ->update('popup_messages', ['status' => self::INACTIVE, 'updated_by' => $userId]);
    }

    private function findMessagesBy(array $parameters): bool
    {
        return $this->db->select('*')
                ->from('popup_messages')
                ->where('page_id', $parameters['page_id'])
                ->where('type', $parameters['type'])
                ->where('receiver_user_id', $parameters['receiver_user_id'])
                ->where('receiver_group_id', $parameters['receiver_group_id'])
                ->where('url', $parameters['url'])
                ->get()
                ->num_rows() > 0;
    }

    /**
     * @param int $type
     * @param int|null $pageId
     * @param string $content
     */
    public function updateContent($type, $pageId, $content)
    {
        $userId = $this->session->userdata('user_id');

        $this->db->where('page_id', $pageId)
            ->where('type', $type)
            ->update('popup_messages', ['content' => $content, 'updated_by' => $userId]);
    }

    /**
     * @param int $type
     * @param int|null $pageId
     * @param int $status
     *
     * @return bool
     */
    public function confirmMessage($type, $pageId, $status)
    {
        $newStatus = $this->removeButtonFromStatus($status);
        $message = $this->getMessage($type, $pageId);

        $user = $this->users_db->get($this->session->userdata('user_id'));

        return $this->db->where('page_id', $pageId)
            ->where('type', $type)
            ->update('popup_messages', [
                'status' => $newStatus,
                'updated_by' => $user['id'],
                'content' => $user['last_name'] . ' ' . $user['first_name'] . ': ' . $message['content']
            ]);
    }

    private function removeButtonFromStatus(int $status): int
    {
        if ($status == MessageHelper::ACTIVE_WITH_CONFIRM_BUTTON) {
            return MessageHelper::ACTIVE;
        } else {
            if ($status == MessageHelper::ACTIVE_WITH_CONFIRM_BUTTON_AND_EDITABLE) {
                return MessageHelper::ACTIVE_AND_EDITABLE;
            }
        }

        return $status;
    }

    public function getMessage(int $type, int $pageId): array
    {
        return $this->db->select('*')
            ->from('popup_messages')
            ->where('page_id', $pageId)
            ->where('type', $type)
            ->limit(1)
            ->get()->row_array();
    }
}
