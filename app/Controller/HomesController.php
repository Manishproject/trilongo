<?php

App::uses('AppController', 'Controller');

class HomesController extends AppController
{

    public function beforeFilter()
    {

        parent::beforeFilter();
        $this->Auth->allow('index', 'my_mail', 'thank_you', 'error_page', 'provider_info', 'how_work');
    }

    public function index()
    {
        $this->Session->delete('preprocess_booking');
//        $this->Session->delete('currently_in');
    }

    public function my_mail()
    {
        $this->autoRender = false;
        $this->loadModel('EmailServer');
        $data = $this->EmailServer->find('all', array('limit' => 50, 'order' => array('EmailServer.id' => 'desc')));
        if (!empty($data)) {
            foreach ($data as $m) {
                pr($m['EmailServer']['subject']);
                pr($m['EmailServer']['message']);
            }
        }
    }

    public function thank_you()
    {

    }

    public function error_page()
    {

    }

    public function how_work()
    {

    }

    public function read_message()
    {
        $this->autoRender = false;
        $response = array();
        $response['message'] = "success";
        $response['status'] = 0;
         $message_id = base64_decode($this->request->data['message_id']);
        if (isset($message_id)) {
            // check message id is valid or not
            $this->loadModel('MessageIndex');
            $message_check = $this->MessageIndex->find("first", array('conditions' => array('MessageIndex.id'=>$message_id,'MessageIndex.recipient_id' => ME, 'MessageIndex.is_read' => 0, 'MessageIndex.deleted' => 0)));
            if (isset($message_check) && !empty($message_check)) {
                // update message index table
                $message_update = array();
                $message_update['MessageIndex']['id'] = $message_id;
                $message_update['MessageIndex']['is_read'] = 1;
                if($this->MessageIndex->save($message_update)){
                    $response['message'] = "Message read successfully";
                    $response['status'] = 1;
                }else{
                    $response['message'] = "Sorry message is not updated";
                }
            } else {
                $response['message'] = "Sorry something went wrong.Please try again";
            }
        } else {
            $response['message'] = "Sorry something went wrong.Please try again";
        }
        echo json_encode($response);
    }
    public function delete_message()
    {
        $this->autoRender = false;
        $response = array();
        $response['message'] = "success";
        $response['status'] = 0;
        $message_id = base64_decode($this->request->data['message_id']);
        if (isset($message_id)) {
            // check message id is valid or not
            $this->loadModel('MessageIndex');
            $message_check = $this->MessageIndex->find("first", array('conditions' => array('MessageIndex.id' =>$message_id,'MessageIndex.recipient_id' => ME, 'MessageIndex.deleted' => 0)));
            if (isset($message_check) && !empty($message_check)) {
                // update message index table
                $message_update = array();
                $message_update['MessageIndex']['id'] = $message_id;
                $message_update['MessageIndex']['is_read'] = 1;
                $message_update['MessageIndex']['deleted'] = 1;
                if($this->MessageIndex->save($message_update)){
                    $response['message'] = "Message deleted successfully";
                    $response['status'] = 1;
                }else{
                    $response['message'] = "Sorry message is not updated";
                }
            } else {
                $response['message'] = "Sorry something went wrong.Please try again";
            }
        } else {
            $response['message'] = "Sorry something went wrong.Please try again";
        }
        echo json_encode($response);
    }

}
