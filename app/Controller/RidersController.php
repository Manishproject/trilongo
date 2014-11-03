<?php

App::uses('Controller', 'Controller');

class RidersController extends AppController
{

    public $uses = array('User', 'Message', 'MessageIndex');

    public function beforeFilter()
    {
        parent::beforeFilter();
        if ($this->Auth->User('role') == 3 || $this->Auth->User('role') == 1) {
            $this->redirect(SITE_URL . "providers/my_account");
        }
        $rider_info = $this->User->find('first', array('conditions' => array('User.id' => ME)));
        $this->set('rider_info', $rider_info);
    }

    public function index()
    {

    }

    public function my_account()
    {

        $this->loadModel('Reservation');
        $this->paginate = array('conditions' => array('Reservation.user_id' => ME,'OR' =>
            array(
              array('Reservation.is_payment_complete'=>1, 'Reservation.provider_id <>' => 0),
                array('Reservation.your_price <>' => 0)
              )
        ), 'limit' => LIMIT_PAGINATION, 'order' => array('Reservation.id' => 'DESC'));
        $all_reservation_data = $this->paginate('Reservation');
        $this->set('all_reservation_data', $all_reservation_data);

    }

    public function info_edit()
    {
        $this->set('title_for_layout', 'Rider Info');
        $provider_info = $this->User->find('first', array('conditions' => array('User.id' => ME)));
        $country_data = $this->Deem->WorldData();
        $this->set('country_data', $country_data);
        // check country is fill then get fetch state array and pass to the view
        if (isset($provider_info['User']['country_id']) && !empty($provider_info['User']['country_id'])) {
            $state_data = $this->Deem->WorldData($provider_info['User']['country_id']);
            $this->set('state_data', $state_data);
        }


        if ($this->request->is('post') || $this->request->is('put')) {
            $this->User->set($this->data);
            $r1 = $this->User->validates();
            if ($r1 && !empty($this->data)) {
                $this->request->data['User']['id'] = ME;
                // move profile image in our directory if user is uploaded
                if (isset($this->request->data['User']['profile_pic']) && is_array($this->request->data['User']['profile_pic']) && $this->request->data['User']['profile_pic']['error'] == 0) {
                    $profile_image_name = $this->DATA->move_photo($this->request->data['User']['profile_pic'], 'profile_photo');
                    $this->request->data['User']['profile_pic'] = $profile_image_name;
                    // check old image is exisr or not if exist then unlink
                    $old_image = WWW_ROOT . "data/profile_photo/" . $provider_info['User']['profile_pic'];
                    if (!empty($provider_info['User']['profile_pic']) && file_exists($old_image)) {
                        unlink($old_image);
                    }
                } else {
                    $this->request->data['User']['profile_pic'] = $provider_info['User']['profile_pic'];
                }

                if ($this->User->save($this->data)) {
                    $this->Session->setFlash(__('Your information is updated successfully.'), 'default', array('class' => 'message success'), 'logmsg');
                    return $this->redirect(array('controller' => 'providers', 'action' => 'my_account'));
                } else {
                    $this->Session->setFlash(__('Sorry information is not updated.Please try again'), 'default', array('class' => 'message error'), 'logmsg');
                }
            }
        } else {
            $this->request->data = $provider_info;
        }
    }

    public function message_listing()
    {
        $this->loadModel('MessageIndex');
        $this->MessageIndex->bindModel(array('belongsTo' => array('Message')));
        $this->paginate = array('conditions' => array('MessageIndex.recipient_id' => ME,'MessageIndex.deleted' => 0), 'limit' => LIMIT_PAGINATION, 'order' => array('MessageIndex.id' => 'DESC'));
        $message_all_data = $this->paginate('MessageIndex');
        $this->set('message_all_data', $message_all_data);
    }

// function for put price show more info
    public function reservation_info_put_price($reservation_id)
    {

        $this->loadModel('Reservation');
        $this->loadModel('Provider');
        $this->Reservation->unbindModelAll();
        $this->Reservation->bindModel(array('hasMany' => array('Proposal' => array('conditions' => array('Proposal.provider_status' => 1)))));
        $reservation_data = $this->Reservation->find('first', array('recursive' => '2', 'conditions' => array('Reservation.id' => $reservation_id, 'Reservation.user_id' => ME)));
//       pr($reservation_data);die;
        if (isset($reservation_data) && !empty($reservation_data)) {
            $this->set('reservation_data', $reservation_data);
        } else {
            $this->Session->setFlash(__('Something went wrong.Please try again.'), 'default', array('class' => 'message error'), 'logmsg');
            return $this->redirect(array('controller' => 'riders', 'action' => 'my_account'));
        }
    }

    // function for put price show more info
    public function reservation_info_choose_provider($reservation_id)
    {
        $this->loadModel('Reservation');
        $this->loadModel('Provider');
        $this->Reservation->unbindModelAll();
        $this->Reservation->bindModel(array('belongsTo' => array('Provider')));
        $this->Provider->bindModel(array('belongsTo' => array('User')));
        $reservation_data = $this->Reservation->find('first', array('recursive' => '2', 'conditions' => array('Reservation.id' => $reservation_id, 'Reservation.user_id' => ME,'Reservation.provider_id <>' => 0,'Reservation.is_payment_complete'=>1)));
        if (isset($reservation_data) && !empty($reservation_data)) {
            $this->set('reservation_data', $reservation_data);
        } else {
            $this->Session->setFlash(__('Something went wrong.Please try again.'), 'default', array('class' => 'message error'), 'logmsg');
            return $this->redirect(array('controller' => 'riders', 'action' => 'my_account'));
        }
    }

    // rider choose one rider from proposal
    public function rider_choose_provider($provider_id, $reservation_id, $proposal_id)
    {
        $this->autoRender = false;
        if ($provider_id && $reservation_id) {
            $this->loadModel('Reservation');
            $this->loadModel('Proposal');

            $this->Reservation->bindModel(array('hasOne' => array('Proposal' => array('conditions' => array('Proposal.id' => $proposal_id)))));
            $this->Proposal->bindModel(array('belongsTo' => array('Provider')));
            $reservation_data = $this->Reservation->find('first', array('recursive' => 2, 'conditions' => array('Reservation.id' => $reservation_id, 'Reservation.user_id' => ME)));
            if (isset($reservation_data) && !empty($reservation_data)) {
                // update table reservation (Provider-id,provider_status)
                $reservation_info = array();
                $reservation_info['Reservation']['provider_id'] = $provider_id;
                $reservation_info['Reservation']['provider_status'] = 1;
                $trilongo_fee = number_format($reservation_data['Proposal']['total_price'] * TRILONGO_PER_FEE,2,'.', '');
                $total_amount = number_format(($reservation_data['Proposal']['total_price']+$trilongo_fee),2,'.', '');
                $reservation_info['Reservation']['total_amount'] = $total_amount;
                $reservation_info['Reservation']['service_charge'] = $trilongo_fee;
                $reservation_info['Reservation']['provider_show_amount'] = $reservation_data['Proposal']['total_price'];
                $reservation_info['Reservation']['id'] = $reservation_id;
                if ($this->Reservation->save($reservation_info)) {
                    // update proposal table means rider choose this provider
                    $proposal_array = array();
                    $proposal_array['Proposal']['id'] = $proposal_id;
                    $proposal_array['Proposal']['rider_status'] = 1;
                    $this->Proposal->save($proposal_array);
                    // insert into message table that rider choose your proposal
                    $message_id = $this->DATA->send_message('rider_choose_proposal', 2, $reservation_data['Proposal']['Provider']['user_id'], $reservation_data['Reservation']['id'], array());
                    $this->loadModel('MessageIndex');
                    $this->MessageIndex->send($message_id, $message_id, $reservation_data['Proposal']['Provider']['user_id'], '2');
                    // then redirect to payment section
                    $this->redirect(array('controller' => 'reservations', 'action' => 'payment', $reservation_data['Reservation']['service_id'], $reservation_id));
                } else {
                    $this->Session->setFlash(__('Something went wrong.Please try again.'), 'default', array('class' => 'message error'), 'logmsg');
                    return $this->redirect(array('controller' => 'riders', 'action' => 'my_account'));
                }
            } else {
                $this->Session->setFlash(__('Something went wrong.Please try again.'), 'default', array('class' => 'message error'), 'logmsg');
                return $this->redirect(array('controller' => 'riders', 'action' => 'my_account'));
            }
        } else {
            $this->Session->setFlash(__('Something went wrong.Please try again.'), 'default', array('class' => 'message error'), 'logmsg');
            return $this->redirect(array('controller' => 'riders', 'action' => 'my_account'));
        }
    }
    

}

?>
