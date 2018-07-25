<?php

use Services\Messages\Messages;

/**
 * @property \CI_Input $input
 * @property \CI_Loader $load
 * @property \CI_Output $output
 */
class PopupMessages extends MY_Controller
{
    /** @var int $userId */
    private $userId;

    public function __construct()
    {
        parent::__construct();

        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }

        $this->lang->load('general', $this->session->userdata('language_name'));
        $this->lang->load('dashboard', $this->session->userdata('language_name'));
        $this->lang->load('messages', $this->session->userdata('language_name'));

        $this->userId = $this->session->userdata('user_id');
    }

    public function getMessages()
    {
        $output = $this->messagesDb->getAllUserMessages($this->userId);

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($output));
    }

    public function markMessagesAsInactive()
    {
        $pageId = $this->input->post('pageId');
        $type = $this->input->post('type');

        $this->messagesDb->markMessagesAsInactive($type, $pageId);
    }

    public function confirmMessage()
    {
        $pageId = $this->input->post('pageId');
        $type = $this->input->post('type');
        $status = $this->input->post('status');

        $this->messagesDb->confirmMessage($type, $pageId, $status);
        $message = $this->messagesDb->getMessage($type, $pageId);

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($message['content']));
    }

    public function updateMessagesContent()
    {
        $pageId = $this->input->post('pageId');
        $type = $this->input->post('type');
        $content = $this->input->post('content');

        $this->messagesDb->updateContent($type, $pageId, $content);
    }

    public function getMessage()
    {
        $pageId = $this->input->post('pageId');
        $type = $this->input->post('type');

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($this->messagesDb->getMessage($type, $pageId)));
    }
}
