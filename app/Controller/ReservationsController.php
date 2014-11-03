<?php
/**
 * Services controller.
 */

App::uses('AppController', 'Controller');

class ReservationsController extends AppController
{

    public $uses = array('World', 'User', 'Reservation');
    public $components = array("Paypal");
    public $vehicletype;

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow('preprocess_booking', 'providers', 'getMapLocation', 'getCurrencyExchangeRate', 'setcurrentlocation', 'thankyou');
        if ($this->Auth->User('role') == 3) {
            $this->Session->setFlash(__('Sorry you cannot book as provider.'), 'default', array('class' => 'message error'), 'logmsg');
            $this->redirect(SITE_URL . "providers/my_account");
        } elseif ($this->Auth->User('role') == 1) {
            $this->Session->setFlash(__('Sorry you cannot book as admin.'), 'default', array('class' => 'message error'), 'logmsg');
            $this->redirect(SITE_URL);
        }
        $this->set('service_id', 0); // we can override it later

        $country_data = $this->Deem->WorldData();

        $this->set('Countries', $country_data);
        $this->vehicletype = $this->Term->find('list',
            array('recursive' => -1, 'conditions' => array('Term.vocabulary_id' => 2)));

    }
    public function index()
    {
        $this->redirect('/');
    }


    public function setcurrentlocation($currently_in = null, $country_name = null)
    {
        $response = array('success' => 0);
        $this->layout = false;
        $this->autoRender = false;
        $this->Session->delete('preprocess_booking');
        if (isset($currently_in) && isset($country_name)) {
            $country_code_name = array($currently_in, $country_name);
            $this->Session->write('current_location', $country_code_name, true, '2 weeks');
        }
        if (isset($_GET['currently_in'])) {
            $country_code_name = array($_GET['currently_in'], $_GET['country_name']);
            $this->Session->write('current_location', $country_code_name, true, '2 weeks');
            $response['success'] = 1;
        }

        if (!$this->request->is('ajax')) {
            $this->redirect($this->referer());
        } else {
            echo json_encode($response);
        }
    }


    public function preprocess_booking($service_id = NULL)
    {

        $this->autoRender = false;

        if ($this->request->is('post')) {
            $this->Session->write('preprocess_booking', $this->request->data);
            if (ME) {
                $this->redirect(array('action' => 'book', $service_id));
            } else {
                $return_url = "?return_url=" . urlencode(SITEURL . "reservations/book/" . $service_id);
                $this->redirect(array('controller' => 'Users', 'action' => 'login', $return_url));
            }


        }
        $this->redirect('/');
    }

    public function book($service_id = NULL)
    {
        if (!$this->request->is('post')) {
            $preprocess_booking_info = $this->Session->read('preprocess_booking');
            if (isset($preprocess_booking_info) && !empty($preprocess_booking_info)) {
                if (isset($preprocess_booking_info['SearchDriver']) && !empty($preprocess_booking_info['SearchDriver'])) {
                    $this->request->data['Reservation']['service_start_date'] = $preprocess_booking_info['SearchDriver']['service_start_date'];
                    $this->request->data['Reservation']['service_end_date'] = $preprocess_booking_info['SearchDriver']['service_end_date'];
                    $this->request->data['Reservation']['pickup_location'] = $preprocess_booking_info['SearchDriver']['pick_up'];
                    $this->request->data['Reservation']['drop_off_location'] = $preprocess_booking_info['SearchDriver']['drop_off'];
                }
                if (isset($preprocess_booking_info['SearchTaxi']) && !empty($preprocess_booking_info['SearchTaxi'])) {
                    $this->request->data['Reservation']['service_start_date'] = $preprocess_booking_info['SearchTaxi']['service_start_date'];
                    $this->request->data['Reservation']['service_end_date'] = $preprocess_booking_info['SearchTaxi']['service_end_date'];
                    $this->request->data['Reservation']['pickup_location'] = $preprocess_booking_info['SearchTaxi']['pick_up'];
                    $this->request->data['Reservation']['drop_off_location'] = $preprocess_booking_info['SearchTaxi']['drop_off'];
                }
                if (isset($preprocess_booking_info['SearchVehicle']) && !empty($preprocess_booking_info['SearchVehicle'])) {
                    $this->request->data['Reservation']['service_start_date'] = $preprocess_booking_info['SearchVehicle']['service_start_date'];
                    $this->request->data['Reservation']['service_end_date'] = $preprocess_booking_info['SearchVehicle']['service_end_date'];
                    $this->request->data['Reservation']['pickup_location'] = $preprocess_booking_info['SearchVehicle']['pick_up'];
                    $this->request->data['Reservation']['drop_off_location'] = $preprocess_booking_info['SearchVehicle']['drop_off'];
                }
            }
        }
        // if service id is ===3
//        then find vechile type from database


        if ($service_id == 3) {
            $this->loadModel('VehicleType');
            $vehicle_type = $this->VehicleType->find('list', array('conditions' => array('VehicleType.status' => 1)));

            $this->set('vehicle_type', $vehicle_type);
        }
        $allowed_services = array(1 => 'driver', 2 => 'taxi', 3 => 'vehicle');
        if (
        (!in_array($service_id, $allowed_services) && !in_array($service_id, array_keys($allowed_services)))
        ) {
            throw new NotFoundException();
        }


        if ($service_id == 'driver' || $service_id == 1) {
            $service_id = 1;
            $this->set('title_for_layout', 'Hire a Driver');
            $this->render('hire_driver');
        } else if ($service_id == 'taxi' || $service_id == 2) {
            $service_id = 2;
            $this->set('title_for_layout', 'Book a Taxi');
            $this->render('book_taxi');

        } else if ($service_id == 'vehicle' || $service_id == 3) {
            $service_id = 3;
            $this->set('title_for_layout', 'Rent a Vehicle');
            $this->render('rent_vehicle');
        }


        $this->set('service_id', $service_id);

        if ($this->request->is('post') && !empty($this->request->data)) {
            $this->__process_reservation_basicinfo_step1($service_id);
        }
    }


    public function payment($service_id = NULL, $reservation_id = null)
    {

        $this->set('title_for_layout', 'Payment');
        $this->loadModel('Provider');
        $this->Reservation->bindModel(array('belongsTo' => array('Provider','User')));
        $this->Provider->bindModel(array('belongsTo' => array('User')));
        $reservation = $this->Reservation->find('first', array('conditions' => array('Reservation.id' => $reservation_id), 'recursive' => 2));
//        pr($reservation);die;




        if (isset($reservation) && !empty($reservation)) {
            if (isset($reservation['Reservation']['is_payment_complete']) && $reservation['Reservation']['is_payment_complete'] == 0) {
                if ((isset($reservation['Reservation']['provider_id']) && $reservation['Reservation']['provider_id'] === "0") || empty($reservation['Reservation']['provider_id'])) {
                    $this->Session->setFlash(__('Please choose provider'), 'default', array('class' => 'message error'), 'logmsg');
                    $this->redirect(array('controller' => 'reservations', 'action' => 'select_provider', $service_id, $reservation_id));
                } else if ((isset($reservation['Reservation']['total_amount']) && $reservation['Reservation']['total_amount'] === "0") || empty($reservation['Reservation']['total_amount'])) {
                    // if provider is choose but total amount is not set due to some reason
//                    then update total amount
                    $total_cost = $this->DATA->calculated_price($reservation, $reservation['Reservation']['provider_id']);
                    $trilongo_fee = number_format($total_cost * TRILONGO_PER_FEE, 2, '.', '');
                    $total_amount = number_format(($total_cost + $trilongo_fee), 2, '.', '');
                    $this->request->data['Reservation']['total_amount'] = $total_amount;
                    $this->request->data['Reservation']['id'] = $reservation['Reservation']['id'];
                    $this->request->data['Reservation']['service_charge'] = $trilongo_fee;
                    $this->request->data['Reservation']['provider_show_amount'] = $total_cost;
                    $this->Reservation->save($this->request->data['Reservation']);

                }

                $this->set('reservation', $reservation);
                $this->set('payable_amount', $reservation['Reservation']['total_amount']);
                $this->set('service_charge', $reservation['Reservation']['service_charge']);
                $this->set('provider_show_payment', $reservation['Reservation']['provider_show_amount']);
                if ($this->request->is('post') || $this->request->is('put')) {
                    $this->__process_reservation_pay_step2($reservation);
                    if (!empty($this->payment_error))
                        $this->Session->setFlash($this->payment_error, 'default', array('class' => 'message error'), 'logmsg');

                    if ($this->payment_success === true) {

                        // insert into message table two rows..
                        // first for your payment is completed
                        // second for provider that you are select for this reservation

                        $message_id = $this->DATA->send_message('payment_complete', 2, ME, $reservation['Reservation']['id'], array());
                        $this->loadModel('MessageIndex');
                        $this->MessageIndex->send($message_id, $message_id, ME, '2');

                        // second for provider that you are select for this reservation
                        // if it's not a proposal payment
                        if ($reservation['Reservation']['provider_status'] == 0) {
                            $message_id = $this->DATA->send_message('provider_received_new_reservation', 3, $reservation['Provider']['User']['id'], $reservation['Reservation']['id'], array());
                            $this->MessageIndex->send($message_id, $message_id, $reservation['Provider']['User']['id'], '3');
                        }

                        // send email to provider for that you choose by rider

                        // template name
                        $template_name = "";
                        if ($reservation['Reservation']['service_id'] == 1) {
                            $template_name = "ProviderChooseHireDriver";
                        } else if ($reservation['Reservation']['service_id'] == 1) {
                            $template_name = "ProviderChooseBookTaxi";
                        } else if ($reservation['Reservation']['service_id'] == 1) {
                            $template_name = "ProviderChooseRentVehicle";
                        }

                        $arr = array('USERNAME' => ucfirst($reservation['Provider']['User']['first_name']));
                        $this->DATA->AppMail($reservation['Provider']['User']['email'], $template_name, $arr);

                        // send rider email that payment complete
                        $arr = array(
                            'USERNAME' => ucfirst($reservation['User']['first_name']),
                            'TOTAL-PRICE' => "$" . number_format($reservation['Reservation']['total_amount'], 2),
                            'PICKUPLOCATION' => $reservation['Reservation']['pickup_location'],
                            'DROPOFFLOCATION' => $reservation['Reservation']['drop_off_location'],
                            'SERVICE-START-DATE-TIME' => date("Y-m-d h:i A", strtotime($reservation['Reservation']['service_start_date_time'])),
                            'SERVICE-END-DATE-TIME' => date("Y-m-d h:i A", strtotime($reservation['Reservation']['service_end_date_time'])),
                        );
                        $this->DATA->AppMail($reservation['User']['email'], 'PaymentCompleteRider', $arr);
                        $this->redirect(array('action' => 'provider_info', $reservation['Reservation']['provider_id'], $reservation_id));
                    }
                    $this->request->data['PaymentLog']['amount'] = $reservation['Reservation']['total_amount'];
                }
            } else {
                $this->Session->setFlash(__('Payment is already done for this reservation'), 'default', array('class' => 'message error'), 'logmsg');
                $this->redirect(array('controller' => 'riders', 'action' => 'my_account'));
            }
        } else {
            $this->Session->setFlash(__('Sorry reservation is not found'), 'default', array('class' => 'message error'), 'logmsg');
            $this->redirect(array('controller' => 'reservations', 'action' => 'book', $service_id));
        }

    }

    /*STEP 3*/
    public function select_provider($service_id = NULL, $reservation_id = null)
    {
        $this->Reservation->unbindModelAll();
        $this->Reservation->bindModel(array('belongsTo' => array('User')));
        $reservation = $this->Reservation->findById($reservation_id);
        if (isset($this->login_user_detail) && !empty($this->login_user_detail)) {
            // check country is fill then get fetch state array and pass to the view
            if (isset($this->login_user_detail['User']['country_id']) && !empty($this->login_user_detail['User']['country_id'])) {
                $state_data = $this->Deem->WorldData($this->login_user_detail['User']['country_id']);
                $this->set('state_data', $state_data);
            }
        }
        if (isset($reservation) && !empty($reservation)) {
            // check provider is already selected or not
            if ($reservation['Reservation']['provider_id'] != 0) {
                $this->redirect(array('controller' => 'reservations', 'action' => 'payment', $reservation['Reservation']['service_id'], $reservation['Reservation']['id']));
            } elseif ($reservation['Reservation']['your_price'] != 0) {
                $this->Session->setFlash(__('you already put your price for this reservation'), 'default', array('class' => 'message error'), 'logmsg');
                $this->redirect(array('controller' => 'riders', 'action' => 'my_account'));
            }
            $this->set('reservation', $reservation);
            $condition = array();
            $condition['User.role'] = AGENT_ROLE;
            $this->User->bindModel(array('hasOne' => array('Provider')));
            // for driver all three option ....km.daily,hours
            if ($service_id == 1) {
                $suggested_provider = $this->DATA->suggested_provider($reservation);
            } else if ($service_id == 2) {
                // for taxi only two option  ....Only the per Hour and Per Kilometer should be the 2 options
                $suggested_provider = $this->DATA->suggested_provider_for_taxi($reservation);
            } else if ($service_id == 3) {
                // for vehicle only two option  ....Only the per Hour and Per Day should be the 2 options.
                $suggested_provider = $this->DATA->suggested_provider_for_vehicle($reservation);
            }

            if (isset($suggested_provider) && !empty($suggested_provider)) {
                $this->set('suggested_provider', $suggested_provider);
            } else {
                // delete old reservation
                $this->Reservation->delete($reservation_id);
                $this->Session->setFlash(__("Trilongo is not available in your area. please remember to try us out when you're in a trilongo city. Check out trilongo.com in the meantime"), 'default', array('class' => 'message error'), 'logmsg');
                $this->redirect(array('controller' => 'reservations', 'action' => 'book', $service_id));
            }
            if ($this->request->is('post') || $this->request->is('put')) {
                $this->Reservation->id = $reservation_id;
                $this->Reservation->set($this->request->data);
                $validate = $this->Reservation->validates();

                if ($validate) {
                    if ((isset($this->request->data['Reservation']['your_price']) && $this->request->data['Reservation']['your_price'] != "") || (isset($this->request->data['Reservation']['provider_id']) && $this->request->data['Reservation']['provider_id'] != "")) {
                        // check user information saved in user table or not  if not then save in user then process
                        $this->DATA->save_user_contact_info($this->request->data, $reservation);
                        // check max price or min price set or not
                        if (isset($this->request->data['Reservation']['your_price']) && $this->request->data['Reservation']['your_price'] != "") {

                            // boardcast message to all provider
                            $this->loadModel('Reservation');
                            $this->request->data['Reservation']['id'] = $reservation_id;
                            $this->request->data['Reservation']['service_id'] = $service_id;
                            // calculated total amount
                            $your_total_price_type = $this->DATA->calculated_price_type_put_price($reservation, $this->request->data['Reservation']['your_price']);
                            $this->request->data['Reservation']['your_total_price'] = $your_total_price_type[0];
                            $this->request->data['Reservation']['your_price_type'] = $your_total_price_type[1];

                            // pr($this->request->data['Reservation']);die;
                            $this->Reservation->save($this->request->data['Reservation']);

                            $response = $this->boardcast($reservation_id);
                            if ($response['status'] == 1) {
                                // set session for thank you page for reservation
                                $this->Session->write('thankyou_page', 1);
                                $this->Session->setFlash(__($response['message']), 'default', array('class' => 'message success'), 'logmsg');
                                $this->redirect(array('controller' => 'reservations', 'action' => 'thankyou_reservation', $reservation_id));
                            } else {
                                $this->Session->setFlash(__($response['message']), 'default', array('class' => 'message error'), 'logmsg');
                                $this->redirect(array('controller' => 'reservations', 'action' => 'select_provider', $service_id, $reservation_id));
                            }

                        } else if (isset($this->request->data['Reservation']['provider_id']) && $this->request->data['Reservation']['provider_id'] != "") {
                            $this->loadModel('Reservation');
                            $this->request->data['Reservation']['id'] = $reservation_id;
                            $this->request->data['Reservation']['provider_id'] = base64_decode($this->request->data['Reservation']['provider_id']);

                            // calculated total amount
                            $total_cost = $this->DATA->calculated_price($reservation, $this->request->data['Reservation']['provider_id']);
                            $trilongo_fee = number_format($total_cost * TRILONGO_PER_FEE, 2, '.', '');
                            $total_amount = number_format(($total_cost + $trilongo_fee), 2, '.', '');
                            $this->request->data['Reservation']['total_amount'] = $total_amount;
                            $this->request->data['Reservation']['service_charge'] = $trilongo_fee;
                            $this->request->data['Reservation']['provider_show_amount'] = $total_cost;
                            $this->Reservation->save($this->request->data['Reservation']);
                            $this->redirect(array('controller' => 'reservations', 'action' => 'payment', $service_id, $reservation_id));
                        }

                    } else {
                        $this->Session->setFlash(__('Please choose our suggestion or put your price'), 'default', array('class' => 'message error'), 'logmsg');
                    }
                }

            } else {
                $this->request->data = $reservation;
            }
        } else {
            $this->Session->setFlash(__('Sorry Something is wrong.Please try again'), 'default', array('class' => 'message error'), 'logmsg');
            $this->redirect(array('controller' => 'reservations', 'action' => 'book', 1));
        }


    }

    public function thankyou_reservation()
    {
        if ($this->Session->read('thankyou_page')) {
            $this->Session->delete('thankyou_page');
            $this->loadModel('Page');
            $thank_you_page_data = $this->Page->find('first', array('conditions' => array('Page.url' => 'thank-you')));
            $this->set('title_for_layout',$thank_you_page_data['Page']['title']);
            $this->set('thank_you_page_data', $thank_you_page_data);
        } else {
            $this->redirect('/');
        }

    }

    private function __process_reservation_basicinfo_step1($service_id)
    {
        $result = array('success' => false, 'submited' => false);
        if ($this->request->is('post') || $this->request->is('put')) {
            // if service id one then means hire a diver and save hire driver info table also
            if ($service_id == 1) {
                $this->request->data['Reservation']['user_id'] = ME;
                $data = $this->request->data;
                $data['Reservation']['service_start_date_time'] = date("Y-m-d H:i:s", strtotime($this->request->data['Reservation']['service_start_date']));
                $data['Reservation']['service_end_date_time'] = date("Y-m-d H:i:s", strtotime($this->request->data['Reservation']['service_end_date']));
                // set pick up location lat log
                $pick_up_lat_log_city = $this->DATA->Get_Lat_lng_city($data['Reservation']['pickup_location']);

                if ($pick_up_lat_log_city['status'] == "ok") {
                    // pick up lat log and city
                    $data['Reservation']['pickup_loc_lat'] = $pick_up_lat_log_city['lat'];
                    $data['Reservation']['pickup_loc_long'] = $pick_up_lat_log_city['lng'];
                    $data['Reservation']['pick_up_location_city'] = $pick_up_lat_log_city['city_name'];
                    // drop lat log and city
                    $drop_off_lat_log_city = $this->DATA->Get_Lat_lng_city($data['Reservation']['drop_off_location']);
                    if ($drop_off_lat_log_city['status'] == "ok") {

                        $data['Reservation']['drop_off_lat'] = $drop_off_lat_log_city['lat'];
                        $data['Reservation']['drop_off_long'] = $drop_off_lat_log_city['lng'];
                        $data['Reservation']['drop_off_location_city'] = $drop_off_lat_log_city['city_name'];


                        $data['Reservation']['service_id'] = $service_id;
                        $session = $this->Session->read('current_location');
                        $data['Reservation']['currently_in'] = $session[1];
                        if (empty($data['Reservation']['total_passenger'])) {
                            $data['Reservation']['total_passenger'] = 1;
                        }

                        // calculated estimated time in hours
                        $data['Reservation']['estimated_hours'] = $this->DATA->calculate_hours($data['Reservation']['service_start_date_time'], $data['Reservation']['service_end_date_time']);
                        if ($this->Reservation->save($data)) {
                            $reservation_id = $this->Reservation->getLastInsertID();
                            $this->redirect(array('controller' => 'reservations', 'action' => 'select_provider', $service_id, $reservation_id));
                        } else {
                            $this->Session->setFlash(__('Sorry something went wrong.Please try again'), 'default', array('class' => 'message error'), 'logmsg');
                        }
                    } else {
                        $this->Session->setFlash(__('Sorry your drop of location could not found on the map.Please fill valid address'), 'default', array('class' => 'message error'), 'logmsg');
                    }
                } else {
                    $this->Session->setFlash(__('Sorry your pick up location could not found on the map.Please fill valid address'), 'default', array('class' => 'message error'), 'logmsg');
                }
            } // if service id is 2 means book a taxi
            elseif ($service_id == 2) {
                $this->request->data['Reservation']['user_id'] = ME;
                $data = $this->request->data;
                $data['Reservation']['service_start_date_time'] = date("Y-m-d H:i:s", strtotime($this->request->data['Reservation']['service_start_date']));
                $data['Reservation']['service_end_date_time'] = date("Y-m-d H:i:s", strtotime($this->request->data['Reservation']['service_end_date']));
                // set pick up location lat log
                $pick_up_lat_log_city = $this->DATA->Get_Lat_lng_city($data['Reservation']['pickup_location']);
                if ($pick_up_lat_log_city['status'] == "ok") {
                    // pick up lat log and city
                    $data['Reservation']['pickup_loc_lat'] = $pick_up_lat_log_city['lat'];
                    $data['Reservation']['pickup_loc_long'] = $pick_up_lat_log_city['lng'];
                    $data['Reservation']['pick_up_location_city'] = $pick_up_lat_log_city['city_name'];
                    // drop lat log and city
                    $drop_off_lat_log_city = $this->DATA->Get_Lat_lng_city($data['Reservation']['drop_off_location']);
                    if ($drop_off_lat_log_city['status'] == "ok") {

                        $data['Reservation']['drop_off_lat'] = $drop_off_lat_log_city['lat'];
                        $data['Reservation']['drop_off_long'] = $drop_off_lat_log_city['lng'];
                        $data['Reservation']['drop_off_location_city'] = $drop_off_lat_log_city['city_name'];


                        $data['Reservation']['service_id'] = $service_id;
                        $session = $this->Session->read('current_location');
                        $data['Reservation']['currently_in'] = $session[1];
                        if (empty($data['Reservation']['total_passenger'])) {
                            $data['Reservation']['total_passenger'] = 1;
                        }

                        // calculated estimated time in hours
                        $data['Reservation']['estimated_hours'] = $this->DATA->calculate_hours($data['Reservation']['service_start_date_time'], $data['Reservation']['service_end_date_time']);
                        if ($this->Reservation->save($data)) {
                            $reservation_id = $this->Reservation->getLastInsertID();
                            $this->redirect(array('controller' => 'reservations', 'action' => 'select_provider', $service_id, $reservation_id));
                        } else {
                            $this->Session->setFlash(__('Sorry something went wrong.Please try again'), 'default', array('class' => 'message error'), 'logmsg');
                        }
                    } else {
                        $this->Session->setFlash(__('Sorry your drop of location could not found on the map.Please fill valid address'), 'default', array('class' => 'message error'), 'logmsg');
                    }
                } else {
                    $this->Session->setFlash(__('Sorry your pick up location could not found on the map.Please fill valid address'), 'default', array('class' => 'message error'), 'logmsg');
                }
            } elseif ($service_id == 3) {
                $this->request->data['Reservation']['user_id'] = ME;
                $data = $this->request->data;
                $data['Reservation']['service_start_date_time'] = date("Y-m-d H:i:s", strtotime($this->request->data['Reservation']['service_start_date']));
                $data['Reservation']['service_end_date_time'] = date("Y-m-d H:i:s", strtotime($this->request->data['Reservation']['service_end_date']));
                // set pick up location lat log
                $pick_up_lat_log_city = $this->DATA->Get_Lat_lng_city($data['Reservation']['pickup_location']);

                if ($pick_up_lat_log_city['status'] == "ok") {
                    // pick up lat log and city
                    $data['Reservation']['pickup_loc_lat'] = $pick_up_lat_log_city['lat'];
                    $data['Reservation']['pickup_loc_long'] = $pick_up_lat_log_city['lng'];
                    $data['Reservation']['pick_up_location_city'] = $pick_up_lat_log_city['city_name'];
                    // drop lat log and city
                    $drop_off_lat_log_city = $this->DATA->Get_Lat_lng_city($data['Reservation']['drop_off_location']);
                    if ($drop_off_lat_log_city['status'] == "ok") {

                        $data['Reservation']['drop_off_lat'] = $drop_off_lat_log_city['lat'];
                        $data['Reservation']['drop_off_long'] = $drop_off_lat_log_city['lng'];
                        $data['Reservation']['drop_off_location_city'] = $drop_off_lat_log_city['city_name'];


                        $data['Reservation']['service_id'] = $service_id;
                        $session = $this->Session->read('current_location');
                        $data['Reservation']['currently_in'] = $session[1];
                        if (empty($data['Reservation']['total_passenger'])) {
                            $data['Reservation']['total_passenger'] = 1;
                        }

                        // calculated estimated time in hours
                        $data['Reservation']['estimated_hours'] = $this->DATA->calculate_hours($data['Reservation']['service_start_date_time'], $data['Reservation']['service_end_date_time']);

                        if ($this->Reservation->save($data)) {
                            $reservation_id = $this->Reservation->getLastInsertID();
                            $this->redirect(array('controller' => 'reservations', 'action' => 'select_provider', $service_id, $reservation_id));
                        } else {
                            $this->Session->setFlash(__('Sorry something went wrong.Please try again'), 'default', array('class' => 'message error'), 'logmsg');
                        }
                    } else {
                        $this->Session->setFlash(__('Sorry your drop of location could not found on the map.Please fill valid address'), 'default', array('class' => 'message error'), 'logmsg');
                    }
                } else {
                    $this->Session->setFlash(__('Sorry your pick up location could not found on the map.Please fill valid address'), 'default', array('class' => 'message error'), 'logmsg');
                }
            }


        }
        return $result;
    }

    public function __process_reservation_pay_step2($reservation)
    {


        $errors = array();
        $pay_status = 'f';
        $pay_err_msg = "";
        $payment_status = 'fail';
        $this->payment_success = false;
        $user_id = ME;

        if ($this->request->is('post')) {

            $this->loadModel('PaymentLog');
            $this->PaymentLog->set($this->request->data);
            if ($this->PaymentLog->validates()) {

                $cardType = $this->request->data['PaymentLog']['card_type'];
                $cardNo = $this->request->data['PaymentLog']['card_no'];
                $expMonth = $this->request->data['PaymentLog']['card_month'];
                $expYear = $this->request->data['PaymentLog']['card_year'];
                $cvv2 = $this->request->data['PaymentLog']['card_cvv'];
                $uFname = $this->request->data['PaymentLog']['user_fname'];
                $uLname = $this->request->data['PaymentLog']['user_lname'];
                //	$amount   =  $this->request->data['PaymentLog']['amount'];

                // Lets ADD Trilongo Fees.

                $trilongo_fee = $reservation['Reservation']['provider_show_amount'] * TRILONGO_PER_FEE;

                $total_amount = number_format($reservation['Reservation']['total_amount'] + $trilongo_fee, 2);

                $curr_code = "USD";

                $doDirectPaymentResponse = $this->Paypal->DoDirect($cardType, $cardNo, $expMonth, $expYear, $cvv2, $uFname, $uLname, $total_amount, $curr_code);

//                 pr($doDirectPaymentResponse,1);
                if (isset($doDirectPaymentResponse) && !empty($doDirectPaymentResponse) && is_array($doDirectPaymentResponse)) {

                    if (!isset($doDirectPaymentResponse["ACK"]) || empty($doDirectPaymentResponse["ACK"])) {
                        $pay_err_msg = "There was an error while getting your payment. Make sure you have entered correct details and try again!";

                    } else {
                        $payment_status = $doDirectPaymentResponse["ACK"];

                        switch ($payment_status) {
                            case 'Success':
                                $pay_status = 's';
                                break;
                            case 'SuccessWithWarning':
                                $pay_status = 's';
                                break;
                            case 'Failure':
                                $pay_err_msg = $doDirectPaymentResponse["ERRORS"][0]["L_LONGMESSAGE"];
                                $pay_status = 'f';
                                break;
                            default:
                                $pay_err_msg = "There was an error while getting your payment. Make sure you have entered correct details and try again!";
                                break;
                        }

                    }


                } else {
                    $pay_err_msg = "There was an error while getting your payment. Make sure you have entered correct details and try again!";
                }

                if ($pay_status == 's') {
                    $transaction_id = isset($doDirectPaymentResponse["TRANSACTIONID"]) ? $doDirectPaymentResponse["TRANSACTIONID"] : "";
                    // generate payment log in means we collect all amount in admin account
                    $this->PaymentLog->set(
                        array("user_id" => $user_id,
                            "amount" => $reservation['Reservation']['total_amount'],
                            "trilongo_fee" => number_format($trilongo_fee, 2, '.', ''),
                            "reservation_id" => $reservation['Reservation']['id'],
                            "status" => $pay_status,
                            "transaction_id" => $transaction_id,
                            "failure_reason" => $pay_err_msg,
                        )
                    );
                    $this->PaymentLog->save($this->request->data);
                    // set reservation table that payment is done for this reservation

                    $this->Reservation->id = $reservation['Reservation']['id'];
                    $this->Reservation->set('is_payment_complete', true);
                    $this->Reservation->save();
                    // create two entry in transaction table for one is for
                    $this->__create_escrow_transaction($user_id, $reservation);
                    $this->payment_success = true;
                }

            } else {
                $errors = $this->PaymentLog->validationErrors;

            }
        }

        $this->payment_error = $pay_err_msg;
        $this->validate_error = $errors;
    }


    private function __create_escrow_transaction($user_id, $reservation)
    {
        $this->autoRender = false;
        $reservation_data = $this->Reservation->findById($reservation['Reservation']['id']);
        $provider_fund = (PROVIDER_FUND_BEFORE_RESERVATION / 100) * $reservation_data['Reservation']['provider_show_amount'];
        $escrow_fund = $reservation_data['Reservation']['provider_show_amount'] - $provider_fund;

        // two entry inset into payment log one for escrow other for provider request
        //payment_type ==1 means it's escrow entry
        $this->loadModel("TransactionLog");
        $data = array();
        $data['TransactionLog']['user_id'] = $user_id;
        $data['TransactionLog']['provider_id'] = $reservation_data['Reservation']['provider_id'];
        $data['TransactionLog']['payment_type'] = 1;

        // check reservation accept or not
        // if it's a proposal payment means provider accept that reservation
        if ($reservation['Reservation']['provider_status'] == 1) {
            $data['TransactionLog']['status'] = 1;
        } else {
            $data['TransactionLog']['status'] = 0;
        }


        $data['TransactionLog']['reservation_id'] = $reservation['Reservation']['id'];
        $data['TransactionLog']['description'] = "it's escrow payment and it will realise after reservation will be done";
        $data['TransactionLog']['amount'] = $escrow_fund;
        $data['TransactionLog']['currency'] = 'USD';

        $this->TransactionLog->set($data);
        $this->TransactionLog->create(false);
        $this->TransactionLog->save($data);


        //payment_type ==2 means it's request for provider
        $data = array();
        $data['TransactionLog']['user_id'] = $user_id;
        $data['TransactionLog']['provider_id'] = $reservation_data['Reservation']['provider_id'];
        $data['TransactionLog']['payment_type'] = 2;
        // check reservation accept or not
        // if it's a proposal payment means provider accept that reservation
        if ($reservation['Reservation']['provider_status'] == 1) {
            $data['TransactionLog']['status'] = 1;
        } else {
            $data['TransactionLog']['status'] = 0;
        }
        $data['TransactionLog']['reservation_id'] = $reservation['Reservation']['id'];
        $data['TransactionLog']['description'] = "it's request for provider means accept/reject reservation";
        $data['TransactionLog']['amount'] = $provider_fund;
        $data['TransactionLog']['currency'] = 'USD';

        $this->TransactionLog->set($data);
        $this->TransactionLog->create(false);
        $this->TransactionLog->save($data);


        // if it's a proposal payment then send a message to provider that payment is done
        if ($reservation['Reservation']['provider_status'] == 1) {
            $message_id = $this->DATA->send_message('rider_done_payment', 2, $reservation['Provider']['User']['id'], $reservation['Reservation']['id'], array());
            $this->loadModel('MessageIndex');
            $this->MessageIndex->send($message_id, $message_id, $reservation['Provider']['User']['id'], '2');
            $this->loadModel('User');
            // 50% payment goes in the provider account
            if ($this->User->credit($reservation['Provider']['User']['id'], $provider_fund)) {
                $message_id = $this->DATA->send_message('fund_added_your_account', 2, $reservation['Provider']['User']['id'], $reservation['Reservation']['id'], array());
                if ($message_id) {
                    $this->MessageIndex->send($message_id, $message_id, $reservation['Provider']['User']['id'], '2');
                }
            }
        }


    }






    public function passengers($service_id = NULL, $reservation_id = null)
    {
        $this->loadModel('Passenger');
        $reservation_data = $this->Reservation->findById($reservation_id);

        if (isset($reservation_data) && !empty($reservation_data)) {
            $this->set('no_of_pass', $reservation_data['Reservation']['total_passenger']);
            $this->set('reservation', $reservation_data);
            $this->set('title_for_layout', 'Passengers information');
            if ($this->request->is('post')) {

                $this->Reservation->Passenger->set($this->request->data['Passenger']);
                $validate = $this->Reservation->Passenger->validates();
                // pr($this->request->data['Passenger']);die;
                if ($validate && $this->Reservation->Passenger->saveAll($this->request->data['Passenger'])) {
                    $this->redirect(array('controller' => 'reservations', 'action' => 'select_provider', $service_id, $reservation_id));
                } else {
                    $this->Session->setFlash(__('Sorry data is not saved.Please try again'), 'default', array('class' => 'message error'), 'logmsg');
                }

            } else {
                $this->request->data = $reservation_data;
            }
        } else {
            $this->Session->setFlash(__('Sorry Something is wrong.Please try again'), 'default', array('class' => 'message error'), 'logmsg');
            $this->redirect(array('controller' => 'reservations', 'action' => 'book', $service_id));
        }
    }



    /**
     * This function will return reservation Id.
     */
    private function checkValidUrl($service_id)
    {
        if (!$service_id) {
            throw  new NotFoundException();
        }
        $reservation_id = $this->Session->read('reservation_id_' . $service_id);

        if (!$reservation_id) {
            $this->redirect(array('action' => 'book', $service_id));
        }
        return $reservation_id;
    }

    public   function boardcast($rid = NULL)
    {
        $this->autoRender = false;
        $result = array('status' => '0');
        $reservation = $this->Reservation->findById($rid);
        if (!empty($reservation)) {
            // fetch all provider near by location

            // if service id ==3 then call with vehicle_id
            $service_type_id = $this->DATA->get_service_id($reservation);
            if ($reservation['Reservation']['service_id'] == 3) {
                $loadAllProviders = $this->DATA->GetProviderByGeoLocationServiceType($reservation, $service_type_id, BOARD_CAST_MILE, null, $reservation['Reservation']['vehicle_id']);
            } else {
                $loadAllProviders = $this->DATA->GetProviderByGeoLocationServiceType($reservation, $service_type_id, BOARD_CAST_MILE);
            }

            // fetch all provider in our database
            //$loadAllProviders = $this->User->find('all', array('conditions' => array('User.role' => '3', 'User.status' => '1')));
            if (isset($loadAllProviders) && !empty($loadAllProviders)) {
                //SMS ==1 email==2 both ==3
                if ($reservation['Reservation']['communication_option'] == 1) {
                    $this->BoardCastReservationSMS($reservation, $loadAllProviders);
                } else if ($reservation['Reservation']['communication_option'] == 2) {
                    $this->BoardCastReservationEmail($reservation, $loadAllProviders);
                } else if ($reservation['Reservation']['communication_option'] == 3) {
                    $this->BoardCastReservationEmail($reservation, $loadAllProviders);
                    $this->BoardCastReservationSMS($reservation, $loadAllProviders, $siteNotificationFlag = false);
                }
                $result['message'] = 'Your service request has been broadcasted to all Providers';
                $result['status'] = '1';
            } else {
                $result['message'] = 'Sorry provider is not matched to this conditions';
            }

        } else {
            $result['message'] = 'Sorry reservation is not found.Please try again';
        }
        return $result;
    }


    private function BoardCastReservationSMS($reservation, $Providers, $siteNotificationFlag = true)
    {
        $subject = "New offer is received";
        $message = "One rider is looking for services";
        if ($siteNotificationFlag) {
            $message_id = $this->insert_message($reservation, $subject, $message, '1');
        }
        foreach ($Providers as $User) {
            $arr = array();
            // if hire driver then get hire driver template and send email
            if ($reservation['Reservation']['service_id'] == 1) {
                // save in message index table with message id
                if ($siteNotificationFlag) {
                    $this->MessageIndex->send($message_id, $message_id, $User['User']['id'], '1');
                }
                $data_array['SmsServer']['text'] = $this->DATA->create_message_text('HireDriverProviderOffer', array());

            } else if ($reservation['Reservation']['service_id'] == 2) {
                // save in message index table with message id
                if ($siteNotificationFlag) {
                    $this->MessageIndex->send($message_id, $message_id, $User['User']['id'], '1');
                }
                $data_array['SmsServer']['text'] = $this->DATA->create_message_text('BookTaxiProviderOffer', array());
            } else if ($reservation['Reservation']['service_id'] == 3) {
                // save in message index table with message id
                if ($siteNotificationFlag) {
                    $this->MessageIndex->send($message_id, $message_id, $User['User']['id'], '1');
                }
                $data_array['text'] = $this->DATA->create_message_text('RentVehicleProviderOffer', array());
            }
            $data_array['SmsServer']['sms_to'] = $User['User']['phone'];
            $this->DATA->InsertSmsServer($data_array);

        }

    }

    private function BoardCastReservationEmail($reservation, $Providers)
    {
        // pr($reservation);die;
        $this->autoRender = false;
        // save message table for site notification
        $subject = "New offer is received";
        $message = " One rider is looking for services";
        $message_id = $this->insert_message($reservation, $subject, $message, '1');

        foreach ($Providers as $User) {
            $arr = array();
            // if hire driver then get hire driver template and send email
            if ($reservation['Reservation']['service_id'] == 1) {
                $link = SITE_URL . "users/login?return_url=" . urlencode(SITEURL . "providers/offer_detail/" . $reservation['Reservation']['id']);
                $arr = array(
                    'USERNAME' => ucfirst($User['User']['first_name']),
                    'PRICE' => "$" . number_format($reservation['Reservation']['your_price'], 2) . " " . $reservation['Reservation']['your_price_type'],
                    'TOTAL-PRICE' => "$" . number_format($reservation['Reservation']['your_total_price'], 2),
                    'PICKUPLOCATION' => $reservation['Reservation']['pickup_location'],
                    'DROPOFFLOCATION' => $reservation['Reservation']['drop_off_location'],
                    'SERVICE-START-DATE-TIME' => date("Y-m-d h:i A", strtotime($reservation['Reservation']['service_start_date_time'])),
                    'SERVICE-END-DATE-TIME' => date("Y-m-d h:i A", strtotime($reservation['Reservation']['service_end_date_time'])),
                    'RESERVATIONLINK' => $link,
                );
                // save in message index table with message id
                //last variable for message_type
                $this->MessageIndex->send($message_id, $message_id, $User['User']['id'], '1');
                // send email to provider
                $this->DATA->AppMail($User['User']['email'], 'BroadCastHireDriver', $arr);
            } else if ($reservation['Reservation']['service_id'] == 2) {
                $link = SITE_URL . "users/login?return_url=" . urlencode(SITEURL . "providers/offer_detail/" . $reservation['Reservation']['id']);
                $arr = array(
                    'USERNAME' => ucfirst($User['User']['first_name']),
                    'PRICE' => "$" . number_format($reservation['Reservation']['your_price'], 2) . " " . $reservation['Reservation']['your_price_type'],
                    'TOTAL-PRICE' => "$" . number_format($reservation['Reservation']['your_total_price'], 2),
                    'PICKUPLOCATION' => $reservation['Reservation']['pickup_location'],
                    'DROPOFFLOCATION' => $reservation['Reservation']['drop_off_location'],
                    'SERVICE-START-DATE-TIME' => date("Y-m-d h:i A", strtotime($reservation['Reservation']['service_start_date_time'])),
                    'SERVICE-END-DATE-TIME' => date("Y-m-d h:i A", strtotime($reservation['Reservation']['service_end_date_time'])),
                    'RESERVATIONLINK' => $link,
                );

                // save in message index table with message id
                //last variable for message_type
                $this->MessageIndex->send($message_id, $message_id, $User['User']['id'], '1');
                $this->DATA->AppMail($User['User']['email'], 'BroadCastHireTaxi', $arr);
            } else if ($reservation['Reservation']['service_id'] == 3) {
                $link = SITE_URL . "users/login?return_url=" . urlencode(SITEURL . "providers/offer_detail/" . $reservation['Reservation']['id']);
                $arr = array(
                    'USERNAME' => ucfirst($User['User']['first_name']),
                    'PRICE' => "$" . number_format($reservation['Reservation']['your_price'], 2) . " " . $reservation['Reservation']['your_price_type'],
                    'TOTAL-PRICE' => "$" . number_format($reservation['Reservation']['your_total_price'], 2),
                    'PICKUPLOCATION' => $reservation['Reservation']['pickup_location'],
                    'DROPOFFLOCATION' => $reservation['Reservation']['drop_off_location'],
                    'SERVICE-START-DATE-TIME' => date("Y-m-d h:i A", strtotime($reservation['Reservation']['service_start_date_time'])),
                    'SERVICE-END-DATE-TIME' => date("Y-m-d h:i A", strtotime($reservation['Reservation']['service_end_date_time'])),
                    'RESERVATIONLINK' => $link,
                );

                // save in message index table with message id
                //last variable for message_type
                $this->MessageIndex->send($message_id, $message_id, $User['User']['id'], '1');
                $this->DATA->AppMail($User['User']['email'], 'BroadCastHireVehicle', $arr);
            }
        }
    }

    function insert_message($reservation, $subject, $message, $message_type)
    {
        $this->loadModel('Message');
        $this->loadModel('MessageIndex');
        return $message_id = $this->Message->Add($subject, $message, $reservation['Reservation']['user_id'], $reservation['Reservation']['id'], $message_type);
    }



    public function cancel($rid = NULL)
    {
        //TODO Future Use
    }

    public function proposal($action = NULL)
    {

    }

    public function detail($reservation_2encode_id)
    {

    }


    public function provider_detail($reservation_2encode_id = NULL)
    {
        $reservation_id = $reservation_2encode_id;
        $this->Reservation->recursive = -1;
        $reservation = $this->Reservation->findById($reservation_id);
        $provider_id = $reservation['Reservation']['provider_id'];
        $providerData = $this->User->findById($provider_id);
        $this->set('reservation', $reservation);
        $this->set('providerData', $providerData);

    }


    // show provider info after payment
    public function provider_info($provider_id, $reservation_id)
    {

        $this->set('title_for_layout', 'Provider Information');
        if (isset($provider_id) && isset($reservation_id)) {
            // check payment is done or not
            if ($this->Session->check('thankyou_page') == true) {
                // delete session
//                $this->Session->delete('thankyou_page');
                $this->loadModel('Provider');
                $this->Reservation->bindModel(array('belongsTo' => array('Provider')));
                $this->Provider->bindModel(array('belongsTo' => array('User')));
                $reservation_data = $this->Reservation->find('first', array('conditions' => array('Reservation.id' => $reservation_id), 'recursive' => 2));
                if (isset($reservation_data) && !empty($reservation_data)) {
                    $this->set('reservation_data', $reservation_data);
                } else {
                    $this->Session->setFlash(__('Sorry something went wrong.Please Try again'), 'default', array('class' => 'message error'), 'logmsg');
                    $this->redirect(array('controller' => 'riders', 'action' => 'my_account'));
                }

            } else {
                $this->Session->setFlash(__('Sorry something went wrong.Please Try again'), 'default', array('class' => 'message error'), 'logmsg');
                $this->redirect(array('controller' => 'riders', 'action' => 'my_account'));
            }
        } else {
            $this->Session->setFlash(__('Sorry something went wrong.Please Try again'), 'default', array('class' => 'message error'), 'logmsg');
            $this->redirect(array('controller' => 'riders', 'action' => 'my_account'));
        }
    }


}