<?php

class CronController extends AppController
{

    public $components = array('Email');

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->layout = false;
        $this->autoRender = false;
        $this->Auth->allow('delete_expire_reservation','trilongo_send_sms','trilongo_email','reservation_automatic_complete','add_escrow_amount_provider');

    }

        // this cron job run (1 min or every min) for sending email
    public function trilongo_email($id = null)
    {
        $this->autoRender = false;
        $current_date = TODAYDATE;
        $last_date = date("Y-m-d", strtotime("-5 day", strtotime(TODAYDATE)));
        if ($id == C_URL) {
            $this->loadModel('EmailServer');
            $data = $this->EmailServer->find('all', array('recursive' => -1, 'conditions' => array('OR' => array(array('EmailServer.status' => 0), array('EmailServer.status' => 2))), 'limit' => 10));

            if (!empty($data)) {
                foreach ($data as $d) {

                    if (filter_var($d['EmailServer']['email_to'], FILTER_VALIDATE_EMAIL)) {

                        if (!empty($d['EmailServer']['email_to']) && !empty($d['EmailServer']['email_from'])) {
                            $this->Email->sendAs = 'html';
                            $this->Email->from = $d['EmailServer']['email_from'];
                            $this->Email->to = $d['EmailServer']['email_to'];
                            $this->Email->subject = $d['EmailServer']['subject'];
                            $this->Email->send($d['EmailServer']['message']);
                            if ($d['EmailServer']['status'] == 0) {
                                $this->EmailServer->updateAll(array('EmailServer.status' => 1), array('EmailServer.id' => $d['EmailServer']['id']));
                            } elseif ($d['EmailServer']['status'] == 2) {
                                $this->EmailServer->updateAll(array('EmailServer.status' => 3), array('EmailServer.id' => $d['EmailServer']['id']));
                            } else {

                            }
                        }
                        echo "total " . count($data) . " has been send  <hr> thank you  <br>";
                    }

                }

            } else {
                echo "No email <hr> thank you  <br>";
            }

            /*
             * Delete old emails
             * */
            $this->EmailServer->deleteAll(array('EmailServer.status' => 1, 'EmailServer.created <' => $last_date));
        } else {
            echo "access not allowed <hr> thank you  <br>";
        }
    }

    // this cron job run (every 8 hours) for auto complete reservation
// function for reservation automatic complete mark after 4 days
    function reservation_automatic_complete($id = null)
    {
        $this->autoRender = false;
        $last_date = date("Y-m-d H:i:s", strtotime("-4 day", strtotime(TODAYDATE)));
        if ($id == C_URL) {
            $this->loadModel('TransactionLog');
            $this->TransactionLog->bindModel(array('belongsTo' => array('Reservation' => array('type' => 'inner', 'conditions' => array('Reservation.service_end_date_time <=' => $last_date, 'Reservation.is_payment_complete' => 1)))));
            $all_TransactionLog_data = $this->TransactionLog->find('all', array('conditions' => array('TransactionLog.reservation_status' => 0), 'limit' => 10));

            if (isset($all_TransactionLog_data) && !empty($all_TransactionLog_data)) {
                $save_many_transaction_Log_data = array();
                foreach ($all_TransactionLog_data as $TransactionLog_data) {
                    $save_many_transaction_Log_data[]['TransactionLog'] = array('reservation_status' => 1, 'id' => $TransactionLog_data['TransactionLog']['id']);
                }
                $this->TransactionLog->saveMany($save_many_transaction_Log_data, array('deep' => true));
            } else {
                echo "Sorry no reservation is completed";
            }
        } else {
            echo "access not allowed <hr> thank you  <br>";
        }
    }
  // this cron job run (every 8 hours) for add escrow amount in provider's account
    // function for send 50% account in provider account after 6 days
    function add_escrow_amount_provider($id = null)
    {
        $this->autoRender = false;
        $last_date = date("Y-m-d H:i:s", strtotime("-6 day", strtotime(TODAYDATE)));
        if ($id == C_URL) {
            $this->loadModel('Reservation');
            $this->loadModel('Provider');

            $this->Reservation->unbindModelAll();
            $this->Reservation->bindModel(array('hasOne' => array('PaymentLog')));

            $this->Reservation->bindModel(array('belongsTo' => array('Provider')));
            $this->Provider->bindModel(array('belongsTo' => array('User')));
            //  payment_type==1 means it's escrow amount'
            //'TransactionLog.escrow_status'=>0 means escrow amount is not realize
            // 'TransactionLog.reservation_status'=>1 reservation is done
            $this->Reservation->bindModel(array('hasOne' => array('TransactionLog' => array('type' => 'inner', 'conditions' => array('TransactionLog.payment_type' => 1, 'TransactionLog.escrow_status' => 0, 'TransactionLog.reservation_status' => 1)))));
            $escrow_amount_data = $this->Reservation->find('all', array('recursive' => 2, 'conditions' => array('Reservation.service_end_date_time <=' => $last_date, 'Reservation.is_payment_complete' => 1), 'limit' => 10));
            if (isset($escrow_amount_data) && !empty($escrow_amount_data)) {

                $this->loadModel('TransactionLog');
                $this->loadModel('User');
                foreach ($escrow_amount_data as $escrow_amount) {
                    $dataSource = $this->User->getDataSource();
                    $dataSource->begin();
                    $message_id = $this->DATA->send_message('escrow_amount_realize', 2, $escrow_amount['Provider']['User']['id'], $escrow_amount['Reservation']['id'], array());
                    $this->loadModel('MessageIndex');
                    $fund_added_message = false;
                    if ($message_id) {
                        $this->MessageIndex->send($message_id, $message_id, $escrow_amount['Provider']['User']['id'], '2');
                    }
                    $this->loadModel('User');
                    // remaining amount  goes in the provider account
                    if ($this->User->credit($escrow_amount['Provider']['User']['id'], $escrow_amount['TransactionLog']['amount'])) {
                        $fund_added = true;
                        $message_id = $this->DATA->send_message('fund_added_your_account', 2, $escrow_amount['Provider']['User']['id'], $escrow_amount['Reservation']['id'], array());
                        if ($message_id) {
                            $this->MessageIndex->send($message_id, $message_id, $escrow_amount['Provider']['User']['id'], '2');
                            $fund_added_message = true;
                        }
                    } else {
                        $fund_added = false;
                    }
                    // update TransactionLog table
                    $Transaction_log_update_flag = false;
                    $Transaction_log_data = array();
                    $Transaction_log_data['TransactionLog']['escrow_status'] =1;
                    $Transaction_log_data['TransactionLog']['id'] =$escrow_amount['TransactionLog']['id'];
                    if($this->TransactionLog->save($Transaction_log_data)){
                        $Transaction_log_update_flag = true;
                    }


                    // check message insert ,fund added,TransactionLog table updated ..
                    // if anyone is failure then rollback all section
//                    $fund_added_message = message insert or not
                    // $Transaction_log_update_flag traction table update or not
                    // amount added or not provider account
                    if($fund_added_message && $Transaction_log_update_flag && $fund_added){
                        $dataSource->commit();
                    }else{
                        $dataSource->rollback();
                    }
                }
            }else{
                echo "Sorry no data found";
            }
        } else {
            echo "access not allowed <hr> thank you  <br>";
        }
    }



    // delete un-use reservation
    public function delete_expire_reservation(){
         $last_date = date("Y-m-d H:i:s", strtotime("-1 day", strtotime(TODAYDATE)));
        // find all reservation in which put price and provider is not null
        $this->loadModel('Reservation');
       $expire_reservation =  $this->Reservation->find('all',array('conditions'=>array('Reservation.created <='=>$last_date,
            array('OR'=>array(array('Reservation.your_price'=>NULL),array('Reservation.your_price'=>'0'))),
           array('OR'=>array(array('Reservation.provider_id'=>NULL),array('Reservation.provider_id'=>'0')))
        )));
        pr($expire_reservation);die;
    }

    public function trilongo_send_sms($id = null)
    {
        $this->autoRender = false;
        $last_date = date("Y-m-d", strtotime("-5 day", strtotime(TODAYDATE)));
        if ($id == C_URL) {
            $this->loadModel('SmsServer');
            $data = $this->SmsServer->find('all', array('recursive' => -1, 'conditions' => array(array('SmsServer.status' => 0)), 'limit' => 10));

            if (!empty($data)) {
                foreach ($data as $d) {
                       $people[$d['SmsServer']['sms_to']] =$d['SmsServer']['text'];
                        if (!empty($d['SmsServer']['sms_to'])) {
                            if ($d['SmsServer']['status'] == 0) {
                                $this->SmsServer->updateAll(array('SmsServer.status' => 1), array('SmsServer.id' => $d['SmsServer']['id']));
                            }
                        }
                    }
                echo "total " . count($data) . " has been send  <hr> thank you  <br>";
              $message_response =   $this->DATA->SendSMS($people);

            } else {
                echo "No sms <hr> thank you  <br>";
            }

            /*
             * Delete old emails
             * */
            $this->SmsServer->deleteAll(array('SmsServer.status' => 1, 'SmsServer.created <' => $last_date));
        } else {
            echo "access not allowed <hr> thank you  <br>";
        }
    }






}

?>