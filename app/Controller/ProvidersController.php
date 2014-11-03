<?php

App::uses('Controller', 'Controller');

class ProvidersController extends AppController
{

    public $uses = array('User', 'Message', 'MessageIndex');

    public function beforeFilter()
    {
        parent::beforeFilter();
        if ($this->Auth->User('role') == 2) {
            $this->redirect(SITE_URL . "riders/my_account");
        }else  if ($this->Auth->User('role') == 1) {
            $this->redirect(SITE_URL);
        }
        $this->User->bindModel(array('hasOne' => array('Company')));
        $this->User->bindModel(array('hasMany' => array('ServiceInformation')));
        $provider_info = $this->User->find('first', array('conditions' => array('User.id' => ME)));
        $this->set('provider_info', $provider_info);
//          $result =   $this->DATA->calculated_distance_two_point("pratap nager,jaipur","gopalpura by pass");
//        pr($result);die;
//       echo $result =  $this->DATA->get_state_city_country("jaipur","kota");die;
    }

    public function my_account()
    {

    }

    public function company_info_edit()
    {
        $this->set('title_for_layout', 'Company Info');
    }

    public function services_info_edit()
    {
        $this->set('title_for_layout', 'Services Info');
    }


    public function add_driver()
    {
        $this->set('title_for_layout', 'Add Driver');
        if ($this->request->is('post') || $this->request->is('put')) {
            $this->loadModel('Driver');
            $this->request->data['Driver']['provider_id'] = PROVIDERID;
            if ($this->Driver->save($this->request->data)) {
                $this->Session->setFlash(__("You've successfully added a driver."), 'default', array('class' => 'message success'), 'logmsg');
                return $this->redirect(array('controller' => 'providers', 'action' => 'driver_listing'));
            } else {
                $this->Session->setFlash(__('Sorry something is wrong.Please try again'), 'default', array('class' => 'message error'), 'logmsg');
            }
        }


    }

    // info of provider edit
    public function info_edit()
    {
        $this->set('title_for_layout', 'Provider Info');
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
                    // check old image is exist or not if exist then unlink
                    $old_image = WWW_ROOT . "data/profile_photo/" . $provider_info['User']['profile_pic'];
                    if (!empty($provider_info['User']['profile_pic']) && file_exists($old_image)) {
                        unlink($old_image);
                    }
                } else {
                    $this->request->data['User']['profile_pic'] = $provider_info['User']['profile_pic'];
                }

// find lat log based on address and save in the database

                $address = $this->request->data['User']['country_name'] . " " . $this->request->data['User']['state_name'] . " " . $this->request->data['User']['city'];
                $pick_up_lat_log_city = $this->DATA->Get_Lat_lng_city($address);
                if ($pick_up_lat_log_city['status'] == "ok") {
                    $this->request->data['User']['lat'] = $pick_up_lat_log_city['lat'];
                    $this->request->data['User']['log'] = $pick_up_lat_log_city['lng'];
                    if ($this->User->save($this->data)) {
                        $this->Session->setFlash(__('Your information is updated successfully.'), 'default', array('class' => 'message success'), 'logmsg');
                        return $this->redirect(array('controller' => 'providers', 'action' => 'my_account'));
                    } else {
                        $this->Session->setFlash(__('Sorry information is not updated.Please try again'), 'default', array('class' => 'message error'), 'logmsg');
                    }
                } else {
                    $this->Session->setFlash(__('Sorry we can\'t find your location in map.Please try again'), 'default', array('class' => 'message error'), 'logmsg');
                }
            }
        } else {
            $this->request->data = $provider_info;
        }
    }


    // function for driver listing section
    public function driver_listing()
    {
        $this->set('title_for_layout', 'Driver Listing');
        $this->loadModel('Driver');
        $this->paginate = array('conditions' => array('Driver.provider_id' => PROVIDERID), 'limit' => LIMIT_PAGINATION, 'order' => array('Reservation.id' => 'ASC'));
        $all_driver_data = $this->paginate('Driver');
        $this->set('all_driver_data', $all_driver_data);
    }

    public function message_listing()
    {
        $this->set('title_for_layout', 'Message Listing');
        $this->loadModel('MessageIndex');
        $this->loadModel('Reservation');
        $this->MessageIndex->bindModel(array('belongsTo' => array('Message')));
        $this->MessageIndex->bindModel(array('hasOne' => array(
            'Reservation' => array('foreignKey' => false, 'conditions' => array('Reservation.id=Message.reservation_id'))
        )));
        $this->Reservation->unbindModelAll();
        $this->Reservation->bindModel(array('hasOne' => array(
            'Proposal' => array('conditions' => array('Proposal.provider_id' => PROVIDERID)),
        )));
        $this->paginate = array('recursive' => 2, 'conditions' => array('MessageIndex.recipient_id' => ME, 'MessageIndex.deleted' => 0), 'limit' => LIMIT_PAGINATION, 'order' => array('MessageIndex.id' => 'DESC'));
        $message_all_data = $this->paginate('MessageIndex');
        $this->set('message_all_data', $message_all_data);
    }

    public function offer_detail($reservation_id)
    {
        $this->set('title_for_layout', 'Offer Detail');
        $this->loadModel('Message');
        $this->loadModel('Reservation');
        $this->Message->bindModel(array('belongsTo' => array('Reservation' => array('type' => 'INNER'))));
        $this->Message->bindModel(array('hasOne' => array(
            'MessageIndex' => array('type' => 'INNER', 'conditions' => array('MessageIndex.recipient_id=' . ME)),
        )));
        $this->Reservation->unbindModelAll();
        $this->Reservation->bindModel(array('hasOne' => array(
            'Proposal' => array('conditions' => array('Proposal.provider_id' => PROVIDERID)),
        )));
        // message type ==1 means only offer received form rider
        $message_section = $this->Message->find('first', array('recursive' => 2, 'conditions' => array('Message.reservation_id' => $reservation_id, 'Message.message_type' => 1, 'MessageIndex.recipient_id' => ME)));

        if (isset($message_section['Message']['id']) && !empty($message_section['Message']['id'])) {
            // update message index table
            $message_update = array();
            $message_update['MessageIndex']['id'] = $message_section['MessageIndex']['id'];
            $message_update['MessageIndex']['is_read'] = 1;
            $this->MessageIndex->save($message_update);
        }
        $this->set('message_section', $message_section);
    }

    // confirmation of proposal accept/reject
    public function confirmation_proposal($reservation_id = null)
    {

        $this->autoRender = false;
        $this->loadModel('Reservation');
        if ($this->request->is('post') || $this->request->is('put')) {
            $this->Message->bindModel(array('belongsTo' => array('Reservation' => array('type' => 'INNER'))));
            $this->Message->bindModel(array('hasOne' => array('MessageIndex' => array('type' => 'INNER', 'conditions' => array('MessageIndex.recipient_id=' . ME)))));
            $this->Reservation->unbindModelAll();
            $this->Reservation->bindModel(array('hasOne' => array(
                'Proposal' => array('conditions' => array('Proposal.provider_id' => PROVIDERID)),
            )));
            $message_section = $this->Message->find('first', array('recursive' => 2, 'conditions' => array('Message.reservation_id' => $reservation_id, 'Message.message_type' => 1, 'MessageIndex.recipient_id' => ME)));
            if (isset($this->request->data['OfferAccept']['amount']) && !empty($this->request->data['OfferAccept']['amount'])) {
                if (!empty($message_section)) {
                    //pr($message_section);die;
                    // check proposal already submitted or not
                    if (!array_filter($message_section['Reservation']['Proposal'])) {
                        // insert into proposal table one entry and then insert into message table for notification rider

                        $your_total_price_type = $this->DATA->calculated_price_type_put_price($message_section, $this->request->data['OfferAccept']['amount']);
                        $Proposal_data['Proposal']['reservation_id'] = $reservation_id;
                        $Proposal_data['Proposal']['provider_id'] = PROVIDERID;
                        $Proposal_data['Proposal']['message_id'] = $message_section['Message']['id'];
                        $Proposal_data['Proposal']['amount'] = number_format($this->request->data['OfferAccept']['amount'], 2, '.', '');
                        $Proposal_data['Proposal']['price_type'] = $message_section['Reservation']['your_price_type'];
                        $Proposal_data['Proposal']['total_price'] = number_format($your_total_price_type[0], 2, '.', '');
                        $Proposal_data['Proposal']['provider_status'] = 1;
                        $this->loadModel('Proposal');
                        $this->Proposal->save($Proposal_data);

                        // insert into message table that your offer is approved by provider
                        $message_id = $this->DATA->send_message('reservation_accept_by_provider_put_price', 2, $message_section['Reservation']['user_id'], $message_section['Reservation']['id'], array());
                        $this->loadModel('MessageIndex');
                        $this->MessageIndex->send($message_id, $message_id, $message_section['Reservation']['user_id'], '2');


                        $this->Session->setFlash(__('Now you wait for feedback'), 'default', array('class' => 'message success'), 'logmsg');
                        $this->redirect(array('controller' => 'providers', 'action' => 'message_listing'));
                    } else {
                        $this->Session->setFlash(__('You have already submit proposal for this reservation'), 'default', array('class' => 'message error'), 'logmsg');
                        $this->redirect(array('controller' => 'providers', 'action' => 'message_listing'));
                    }

                } else {
                    $this->Session->setFlash(__('Sorry something went wrong.please try again'), 'default', array('class' => 'message error'), 'logmsg');
                    $this->redirect(array('controller' => 'providers', 'action' => 'message_listing'));
                }
            } else {
                $this->Session->setFlash(__('Please fill valid price'), 'default', array('class' => 'message error'), 'logmsg');
                $this->redirect(array('controller' => 'providers', 'action' => 'offer_detail', $message_section['Message']['reservation_id']));
            }

        } else {
            $this->Session->setFlash(__('Sorry something went wrong.please try again'), 'default', array('class' => 'message error'), 'logmsg');
            $this->redirect(array('controller' => 'providers', 'action' => 'message_listing'));
        }
    }

// cancel reservation by provider
    public function cancel_reservation($reservation_id = null)
    {
        $this->autoRender = false;

        $this->Message->bindModel(array('belongsTo' => array('Reservation' => array('type' => 'INNER'))));
        $this->Message->bindModel(array('hasOne' => array('Proposal' => array('conditions' => array('Proposal.provider_id' => PROVIDERID)), 'MessageIndex' => array('type' => 'INNER', 'conditions' => array('MessageIndex.recipient_id=' . ME)))));
        $message_section = $this->Message->find('first', array('conditions' => array('Message.reservation_id' => $reservation_id, 'Message.message_type' => 1, 'MessageIndex.recipient_id' => ME)));
        //pr($message_section);die;
        if (!empty($message_section)) {

            // check proposal already submitted or not
            if (!array_filter($message_section['Proposal'])) {
                // insert into proposal table one entry and then insert into message table for notification rider
                $Proposal_data['Proposal']['reservation_id'] = $reservation_id;
                $Proposal_data['Proposal']['provider_id'] = PROVIDERID;
                $Proposal_data['Proposal']['message_id'] = $message_section['Message']['id'];
                $Proposal_data['Proposal']['proposed_amount'] = Null;
                $Proposal_data['Proposal']['provider_status'] = 2;
                $this->loadModel('Proposal');

                $this->Proposal->save($Proposal_data);
                // insert into message table that your offer is approved by provider
                $message_id = $this->DATA->send_message('reservation_cancel_by_provider_put_price', 2, $message_section['Reservation']['user_id'], $message_section['Reservation']['id'], array());
                $this->loadModel('MessageIndex');
                $this->MessageIndex->send($message_id, $message_id, $message_section['Reservation']['user_id'], '2');


                $this->Session->setFlash(__('Proposal is rejected.'), 'default', array('class' => 'message success'), 'logmsg');
                $this->redirect(array('controller' => 'providers', 'action' => 'message_listing'));
            } else {
                $this->Session->setFlash(__('You have already submit proposal for this reservation'), 'default', array('class' => 'message error'), 'logmsg');
                $this->redirect(array('controller' => 'providers', 'action' => 'message_listing'));
            }
        } else {
            $this->Session->setFlash(__('Sorry something went wrong.please try again'), 'default', array('class' => 'message error'), 'logmsg');
            $this->redirect(array('controller' => 'providers', 'action' => 'message_listing'));
        }


    }

    // more info for reservation
    public function more_info_reservation($reservation_id)
    {
        $this->loadModel('Reservation');
        $this->loadModel('Message');
        $this->Reservation->bindModel(array('hasOne' => array('Message' => array('conditions' => array('Message.user_id=' . ME)))));
        $this->Message->bindModel(array('hasOne' => array('MessageIndex' => array('type' => 'INNER', 'conditions' => array('MessageIndex.recipient_id=' . ME)))));
        $reservation_data = $this->Reservation->find('first', array('recursive' => 2, 'conditions' => array('Reservation.id' => $reservation_id, 'Reservation.provider_id' => PROVIDERID)));
        if (isset($reservation_data) && !empty($reservation_data)) {
            $this->set('reservation_data', $reservation_data);


            // message type ==1 means only offer received form rider
            if (isset($reservation_data['Message']['id']) && !empty($reservation_data['Message']['id'])) {
                // update message index table
                $message_update = array();
                $message_update['MessageIndex']['id'] = $reservation_data['Message']['MessageIndex']['id'];
                $message_update['MessageIndex']['is_read'] = 1;
                $this->MessageIndex->save($message_update);
            }


        } else {
            $this->Session->setFlash(__('Sorry something went wrong.please try again'), 'default', array('class' => 'message error'), 'logmsg');
            $this->redirect(array('controller' => 'providers', 'action' => 'message_listing'));
        }

    }


    // provider reject reservation after select via rider
    function accept_cancel_after_selection_reservation($reservation_id, $status)
    {
        $this->autoRender = false;
        $this->loadModel('Reservation');
        $this->Reservation->unbindModelAll();
        $this->Reservation->bindModel(array('hasOne' => array('TransactionLog' => array('type' => 'INNER', 'conditions' => array('TransactionLog.payment_type' => 2)))));
        $reservation_data = $this->Reservation->find('first', array('conditions' => array('Reservation.id' => $reservation_id, 'Reservation.provider_id' => PROVIDERID, 'Reservation.provider_status' => 0)));
        //pr($reservation_data);die;
        if (isset($reservation_data) && !empty($reservation_data)) {
            if ($status == 1) {
//means user want accept this reservation
//            update reservation table then update total balance then update payment log table
                //1) update reservation table
                $reservation_info['Reservation']['id'] = $reservation_id;
                $reservation_info['Reservation']['provider_status'] = 1;

                if ($this->Reservation->save($reservation_info)) {
                    $this->loadModel('User');
                    $this->loadModel('PaymentLog');
                    //2) update total balance

                    if ($this->User->credit(ME, $reservation_data['TransactionLog']['amount'])) {
//                       3) update payment log table
                        $this->DATA->update_transaction_log($reservation_data['Reservation']['id'], $reservation_data['Reservation']['provider_id'], 1);
                        // provider accept the reservation


                        $message_id = $this->DATA->send_message('reservation_accept_by_provider_choose_provider', 2, $reservation_data['Reservation']['user_id'], $reservation_id, array());
                        if ($message_id) {
                            $this->loadModel('MessageIndex');
                            $this->MessageIndex->send($message_id, $message_id, $reservation_data['Reservation']['user_id'], '2');
                        }
                        $message_id = $this->DATA->send_message('fund_added_your_account', 2, $reservation_data['Reservation']['user_id'], $reservation_id, array());
                        if ($message_id) {
                            $this->loadModel('MessageIndex');
                            $this->MessageIndex->send($message_id, $message_id, ME, '2');
                        }
//                       $this->Session->setFlash(__('Your reservation is accepted'), 'default', array('class' => 'message success'), 'logmsg');
                        return $this->redirect(array('controller' => 'providers', 'action' => 'message_listing'));
//                       $this->redirect(SITE_URL."providers/message_listing");
                        die;
                    } else {
                        $this->Session->setFlash(__('Sorry something went wrong.please try again1'), 'default', array('class' => 'message error'), 'logmsg');
                        $this->redirect(array('controller' => 'providers', 'action' => 'message_listing'));
                    }
//

                } else {
                    $this->Session->setFlash(__('Sorry something went wrong.please try again'), 'default', array('class' => 'message error'), 'logmsg');
                    $this->redirect(array('controller' => 'providers', 'action' => 'message_listing'));
                }


            } else if ($status == 0) {
                //means user want accept this reservation
                //1) update reservation table
                $reservation_info['Reservation']['id'] = $reservation_id;
                $reservation_info['Reservation']['provider_status'] = 2;
                $this->Reservation->save($reservation_info);


                //  2) update payment log table
                $this->DATA->update_transaction_log($reservation_data['Reservation']['id'], $reservation_data['Reservation']['provider_id'], 2);


                $message_id = $this->DATA->send_message('reservation_cancel_by_provider_choose_provider', 2, $reservation_data['Reservation']['user_id'], $reservation_id, array());
                $this->loadModel('MessageIndex');
                $this->MessageIndex->send($message_id, $message_id, $reservation_data['Reservation']['user_id'], '2');
                $this->Session->setFlash(__('Reservation is canceled'), 'default', array('class' => 'message success'), 'logmsg');
                $this->redirect(array('controller' => 'providers', 'action' => 'message_listing'));

            }
        } else {
            $this->Session->setFlash(__('Sorry something went wrong.please try again'), 'default', array('class' => 'message error'), 'logmsg');
            $this->redirect(array('controller' => 'providers', 'action' => 'message_listing'));
        }

    }

    // withdrawal modules
    public function withdrawal_listing()
    {
        $this->loadModel('Withdrawal');
        $this->paginate = array('conditions' => array('Withdrawal.user_id' => ME, 'Withdrawal.status' => 1), 'limit' => LIMIT_PAGINATION, 'order' => array('Withdrawal.id' => 'DESC'));
        $all_withdrawal_data = $this->paginate('Withdrawal');
        $this->set('all_withdrawal_data', $all_withdrawal_data);

    }

    // withdrawal modules
    public function wallet()
    {
        $this->loadModel('TransactionLog');
        $this->paginate = array('conditions' => array('TransactionLog.provider_id' => PROVIDERID, 'TransactionLog.status' => 1), 'limit' => LIMIT_PAGINATION, 'order' => array('TransactionLog.id' => 'DESC'));
        $all_transaction_log_data = $this->paginate('TransactionLog');
        $this->set('all_transaction_log_data', $all_transaction_log_data);

    }

    public function reservation_detail($reservation_id = null)
    {
//        $this->autoLayout = false;
        if ($reservation_id) {
            $this->loadModel('Reservation');
            $this->Reservation->unbindModelAll();
            $this->Reservation->bindModel(array('belongsTo' => array('User')));
            $reservation_data = $this->Reservation->find('first', array('conditions' => array('Reservation.id' => $reservation_id)));
            $this->set('reservation_data', $reservation_data);


        }
    }


    public function service_info_driver()
    {
        $this->set('title_for_layout', 'Service Info Driver');
        $this->loadModel('ServiceInformation');
        $service_data = $this->ServiceInformation->find('all', array('conditions' => array('ServiceInformation.provider_id' => PROVIDERID, 'ServiceInformation.user_id' => ME, 'ServiceInformation.service_id' => 1)));
        if ($this->request->is('post') || $this->request->is('put')) {

            // check validation service info
            $result = $this->DATA->check_service_validation($this->request->data);
            if ($result['status'] == 1) {
                if ($this->ServiceInformation->saveMany($this->request->data, array('deep' => true))) {
                    $this->Session->setFlash(__('Information is updated successfully'), 'default', array('class' => 'message success'), 'logmsg');
                    return $this->redirect(array('controller' => 'providers', 'action' => 'my_account'));
                } else {
                    $this->Session->setFlash(__('Sorry,information is not updated successfully.Please try again'), 'default', array('class' => 'message error'), 'logmsg');
                }
            } else if ($result['status'] == 0) {
                $this->Session->setFlash(__('Please fill valid value.Please try again'), 'default', array('class' => 'message error'), 'logmsg');
            }

        } else {
            $this->request->data = $service_data;
        }
    }

    public function service_info_taxi()
    {
        $this->set('title_for_layout', 'Service Info Taxi');
        $this->loadModel('ServiceInformation');
        $service_data = $this->ServiceInformation->find('all', array('conditions' => array('ServiceInformation.provider_id' => PROVIDERID, 'ServiceInformation.user_id' => ME, 'ServiceInformation.service_id' => 2)));
        if ($this->request->is('post') || $this->request->is('put')) {

            $result = $this->DATA->check_service_validation($this->request->data);
            if ($result['status'] == 1) {
                if ($this->ServiceInformation->saveMany($this->request->data, array('deep' => true))) {
                    $this->Session->setFlash(__('Information is updated successfully'), 'default', array('class' => 'message success'), 'logmsg');
                    return $this->redirect(array('controller' => 'providers', 'action' => 'my_account'));
                } else {
                    $this->Session->setFlash(__('Sorry,information is not updated successfully.Please try again'), 'default', array('class' => 'message error'), 'logmsg');
                }
            } else if ($result['status'] == 0) {
                $this->Session->setFlash(__('Please fill valid value.Please try again'), 'default', array('class' => 'message error'), 'logmsg');
            }
        } else {
            $this->request->data = $service_data;
        }

    }

    public function service_info_vehicle()
    {
        $this->set('title_for_layout', 'Service Info Vehicle');
        $this->loadModel('ServiceInformation');
        $service_data = $this->ServiceInformation->find('all', array('conditions' => array('ServiceInformation.provider_id' => PROVIDERID, 'ServiceInformation.user_id' => ME, 'ServiceInformation.service_id' => 3)));

        if ($this->request->is('post') || $this->request->is('put')) {
            $result = $this->DATA->check_service_validation($this->request->data);
            if ($result['status'] == 1) {
                if ($this->ServiceInformation->saveMany($this->request->data, array('deep' => true))) {
                    $this->Session->setFlash(__('Information is updated successfully'), 'default', array('class' => 'message success'), 'logmsg');
                    return $this->redirect(array('controller' => 'providers', 'action' => 'my_account'));
                } else {
                    $this->Session->setFlash(__('Sorry,information is not updated successfully.Please try again'), 'default', array('class' => 'message error'), 'logmsg');
                }
            } else if ($result['status'] == 0) {
                $this->Session->setFlash(__('Please fill valid value.Please try again'), 'default', array('class' => 'message error'), 'logmsg');
            }
        } else {
            $this->request->data = $service_data;
        }

    }


}
