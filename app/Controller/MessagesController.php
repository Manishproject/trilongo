<?php
App::uses('Controller', 'Controller');

class MessagesController extends AppController
{
    public $name = 'Messages';
    public $uses = array('User', 'Message', 'MessageIndex');

    public $components = array('RequestHandler');

    public function beforeFilter()
    {
        parent::beforeFilter();

        // Change layout for Ajax requests
        if ($this->request->is('ajax')) {
            $this->layout = 'ajax';
        }
    }

    //Admin Dashboard
    public function admin_index()
    {
        $this->admin_inbox();
    }


    public function admin_new($uid = NULl)
    {
        $this->set('title', 'New Message');

        $uid = $this->Auth->User('id');

        if (!empty($this->data) && $this->request->data) {

            $message = array();
            $message_index = array();

            $message['user_id'] = $uid;
            $message['subject'] = $this->request->data['Message']['subject'];
            $message['body'] = $this->request->data['Message']['body'];
            $participents = $this->request->data['Message']['to'];

            $this->request->data['Message'] = $message;
            if ($this->sent_message($message['user_id'], $message['subject'], $message['body'], 0, $participents)) {
                $this->Session->setFlash(__('<div class="alert alert-info">
			  <button class="close" data-dismiss="alert" type="button">X</button>Mesaage has been successfully sent.</div>', true), 'default', array());

                $this->redirect(array('action' => 'outbox'));

            }

        } else {
            if (isset($_GET['participent']) && $_GET['participent']) {
                $this->request->data['Message']['to'] = explode(',', $_GET['participent']);
                $this->Session->write('destination', 'admin/usermanagers/list');
            }
        }

        $participents = $this->User->find('list', array('conditions' => array('User.id !=' => 1, 'User.status' => 'active'), 'fields' => array('id', 'username')));

        $this->set('participents', $participents);
    }


    /**
     *
     * Enter description here ...
     * @param unknown_type $uid
     * @param unknown_type $subject
     * @param unknown_type $body
     * @param unknown_type $thread_id
     * @param unknown_type $participents same used in users
     */

    public function sent_message($uid, $subject, $body, $thread_id = 0, $participents = array())
    {
        $message_index = array();

        $message['user_id'] = $uid;
        $message['subject'] = $subject;
        $message['body'] = $body;

        $this->request->data['Message'] = $message;
        if (!empty($participents) && $this->Message->save($this->data)) {

            $mid = $this->Message->id;

            $message_index['message_id'] = $mid;
            $message_index['thread_id'] = (!$thread_id) ? $mid : $thread_id;
            $message_index['recipient_id'] = $uid; // First message to self user
            $message_index['is_new'] = 0;
            $message_index['deleted'] = NULL;
            $message_index['status'] = 'outbox';

            $this->request->data['MessageIndex'] = $message_index;

            if ($this->MessageIndex->save($this->data)) { // sending self
                $this->loadModel('Notification');
                foreach ($participents as $user_id) {
                    $message_index['recipient_id'] = $user_id;
                    $message_index['is_new'] = 1;
                    $message_index['status'] = 'inbox';

                    $this->request->data['MessageIndex'] = $message_index;

                    if ($this->MessageIndex->create() && $this->MessageIndex->save($this->data)) { //sending others
                        //Let send email also
                        $userData = $this->User->findById($user_id);
                        $token = array();
                        $token['[user:name]'] = $userData['User']['username'];
                        $token['[message]'] = $body;
                        $this->ChallengeHandler->sendByTemplateSlug($userData['User']['email'], 'private_message', $token); //Send Email Notification on New Message
                        $this->Notification->AddNotification($user_id, $message_index['thread_id'], 'new message in your inbox', 'private_message'); // Site Notification

                    }

                }
            }

            return 1;
        }
        return 0;

    }

    public function admin_outbox()
    {
        $uid = $this->Auth->user('id');

        $condition = array();
        $condition = array('MessageIndex.recipient_id' => $uid);
        $condition['MessageIndex.status'] = 'outbox';
        $condition[] = 'MessageIndex.deleted IS NULL';

        $this->MessageIndex->bindModel(array('belongsTo' => array(
                'Message' => array(
                    'className' => 'Message',
                    'foreignKey' => false,
                    'conditions' => array('Message.id=MessageIndex.thread_id')
                )
            )
            )
        );

        $this->paginate = array(
            'fields' => array('count(MessageIndex.id) as count',
                'Min(MessageIndex.Created) as created',
                'Message.*', 'MessageIndex.*',
                'Max(MessageIndex.is_new) as is_new'),
            'conditions' => $condition,
            'limit' => $this->_Settings['PAGINATION_LIMIT'],
            'order' => array('MessageIndex.created' => 'DESC'),
            'group' => array('MessageIndex.thread_id'),

        );


        $this->set('outbox_message', $this->paginate('MessageIndex'));

    }

    public function admin_inbox()
    {
        $uid = $this->Auth->user('id');

        $condition = array();
        $condition = array('MessageIndex.recipient_id' => $uid);
        $condition['MessageIndex.status'] = 'inbox';
        $condition[] = 'MessageIndex.deleted IS NULL';
        //$this->MessageIndex->bindModel(array('belongsTo'=>array('Message','User'=>array('className'=>'User','foreignKey'=>false,'conditions'=>array('User.id=MessageIndex.recipient_id')))));
        $this->MessageIndex->bindModel(array('belongsTo' => array(
                'Message' => array(
                    'className' => 'Message',
                    'foreignKey' => false,
                    'conditions' => array('Message.id=MessageIndex.thread_id')
                )
            )
            )
        );

        $this->paginate = array(
            'fields' => array('count(MessageIndex.id) as count',
                'Min(MessageIndex.Created) as created',
                'Message.*', 'MessageIndex.*',
                'Max(MessageIndex.is_new) as is_new'),
            'conditions' => $condition,
            'limit' => $this->_Settings['PAGINATION_LIMIT'],
            'order' => array('MessageIndex.created' => 'DESC'),
            'group' => array('MessageIndex.thread_id'),

        );


        $this->set('inbox_message', $this->paginate('MessageIndex'));
        $this->render('admin_index');
    }

    public function admin_trash()
    {

        $uid = $this->Auth->user('id');
        $condition = array();
        $condition = array('MessageIndex.recipient_id' => $uid);

        $condition[] = 'MessageIndex.deleted IS NOT NULL';

        $this->MessageIndex->bindModel(array('belongsTo' => array(
                'Message' => array(
                    'className' => 'Message',
                    'foreignKey' => false,
                    'conditions' => array('Message.id=MessageIndex.thread_id')
                )
            )
            )
        );

        $this->paginate = array(
            'fields' => array('count(MessageIndex.id) as count',
                'Min(MessageIndex.Created) as created',
                'Message.*', 'MessageIndex.*',
                'Max(MessageIndex.is_new) as is_new'),
            'conditions' => $condition,
            'limit' => $this->_Settings['PAGINATION_LIMIT'],
            'order' => array('MessageIndex.created' => 'DESC'),
            'group' => array('MessageIndex.thread_id'),

        );


        $this->set('trash_message', $this->paginate('MessageIndex'));

    }


    public function admin_delete($type = 'thread', $thread_id = 0, $op = 'trash')
    {
        $uid = $this->Auth->user('id');
        $this->autoRender = false;
        $conditions = array('MessageIndex.recipient_id' => $uid, 'MessageIndex.thread_id' => $thread_id);

        if ($type == 'thread') {
            if ($op == 'delete') {
                $this->MessageIndex->deleteAll($conditions, false);
                $this->Session->setFlash(__('<div class="alert alert-info">
					<button class="close" data-dismiss="alert" type="button">X</button>Thead has been successfully deleted.</div>', true), 'default', array());

            } else if ($op == 'trash') {
                $this->MessageIndex->updateAll(array('MessageIndex.deleted' => "'" . date('Y-m-d h:i:s') . "'"), $conditions);

                $this->Session->setFlash(__('<div class="alert alert-info">
					<button class="close" data-dismiss="alert" type="button">X</button>Thead has been successfully trash.</div>', true), 'default', array());


            } else if ($op == 'restore') {

                $this->MessageIndex->updateAll(array('MessageIndex.deleted' => 'NULL'), $conditions);

                $this->Session->setFlash(__('<div class="alert alert-info">
					<button class="close" data-dismiss="alert" type="button">X</button>Thead has been successfully restore.</div>', true), 'default', array());


            } else {

                die('invalid operation');
            }

        } else { // we are deleting individual message


        }

        if ($this->referer() != '/')
            $this->redirect($this->referer());
        else
            $this->redirect(array('action' => 'inbox'));

    }

    public function admin_view($thread_id = NUll)
    {
        $uid = $this->Auth->user('id');
        //  $uid =4;
        if ($this->request->is('post')) {

            if (!empty($this->data) && $this->request->data) {

                $message = array();


                $message['user_id'] = $uid;
                $message['subject'] = $this->request->data['Message']['subject'];
                $message['body'] = $this->request->data['Message']['body'];
                $thread_id = $this->request->data['MessageIndex']['thread_id'];

                $participents = $this->MessageIndex->find('list', array(
                    'conditions' => array('MessageIndex.thread_id' => $thread_id, 'MessageIndex.message_id' => $thread_id, 'MessageIndex.recipient_id !=' . $uid),
                    'fields' => array('MessageIndex.recipient_id')

                ));


                $this->request->data['Message'] = $message;
                if ($this->sent_message($message['user_id'], $message['subject'], $message['body'], $thread_id, $participents)) {
                    $this->Session->setFlash(__('<div class="alert alert-info">
			  <button class="close" data-dismiss="alert" type="button">X</button>Mesaage has been successfully sent.</div>', true), 'default', array());

                    $this->redirect(array('action' => 'view', $thread_id));

                }

            }


        }
        $condition = array();
        $condition['MessageIndex.thread_id'] = $thread_id;
        $condition['MessageIndex.recipient_id'] = $uid;
//	$condition[]='MessageIndex.deleted IS NULL'; we can visit trash message as well
        $this->MessageIndex->bindModel(array('belongsTo' => array(
                'Message' => array(
                    'className' => 'Message',
                    'foreignKey' => false,
                    'conditions' => array('Message.id=MessageIndex.message_id')
                )
            )
            )
        );

        $this->paginate = array(
            'fields' => array('MessageIndex.thread_id', 'MessageIndex.id', 'MessageIndex.message_id', 'MessageIndex.is_new', 'Message.id', 'Message.user_id', 'Message.subject',
                'Message.body', 'MessageIndex.recipient_id'),
            'conditions' => $condition,
            'limit' => $this->_Settings['PAGINATION_LIMIT'],
            'order' => array('MessageIndex.created' => 'ASC'),

        );

        if ($result = $this->paginate('MessageIndex')) {
            $this->set('thread_messages', $result);
            $this->set('thread_id', $thread_id);
            $this->set('user_id', $uid);
        } else {

            throw  new NotFoundException('No conversation found with thread:' . $thread_id);
        }

    }

}

?>
