<?php

App::uses('Controller', 'Controller');

class WebserviceController extends AppController
{

    public $components = array('DATA', 'Paypal', 'Cookie');
    public $uses = array('User');

    //  var $helpers = array('Text', 'Xml','Json');
    public function beforeFilter()
    {
        $this->autoRender = false;
        $this->layout = false;
        $this->Auth->allow(); // allow webservice controller
//        $this->Cookie->write('remember_me_cookie', 'Masters', true, '1 hour');
//        pr($this->Cookie->read('remember_me_cookie'));
    }

    public function index()
    {
        echo "V-S7[&RSPJ%F" . "<br>";
        echo "deemtech";
        $this->autoRender = false;
        $this->Auth->deny();

        echo '<h1>Wecome to trilongo</h1>';
    }

    /**
     * 1. For  Login Page:-
     */
    public function login()
    {
        $data = file_get_contents('php://input');

        $req_data = json_decode($data, true);
        $response = array();
        $response["status"] = 0;
        $username = isset($req_data["email"]) ? $req_data["email"] : "";
        $password = isset($req_data["password"]) ? $req_data["password"] : "";
        if ((empty($username) || empty($password))) {
            $response["message"] = "Please fill all fields";
        } else {

            $password = md5($password); // Lets Md5 now
            $this->User->bindModel(array('hasOne' => array('Provider')));
            $user_data = $this->User->find('first', array('conditions' => array('User.email' => $username), 'password' => $password));

            if ($user_data) {
                if ($user_data['User']["status"] == '0') {
                    $response["message"] = "User is blocked!";
                } else if ($user_data['User']["status"] == '2') {
                    $response["message"] = "User not found!";
                } else {
                    $response["status"] = 1;
                    $response["message"] = "successfully";
                    $response["user_id"] = trim($user_data['User']["id"]);
                    $response["first_name"] = trim($user_data['User']["first_name"]);
                    $response["last_name"] = trim($user_data['User']["last_name"]);
                    $response["role"] = trim($user_data['User']["role"]);
                    $response["profile_image"] = trim($user_data['User']["profile_pic"]);
                    $response["phone_number"] = trim($user_data['User']["phone"]);
                    $response["country_name"] = trim($user_data['User']["country_name"]);
                    $response["country_id"] = trim($user_data['User']["country_id"]);
                    $response["state_name"] = trim($user_data['User']["state_name"]);
                    $response["state_id"] = trim($user_data['User']["state_id"]);
                    $response["city"] = trim($user_data['User']["city"]);
                    $response["address"] = trim($user_data['User']["address"]);
                    $response["gender"] = trim($user_data['User']["gender"]);
                    $response["about"] = trim($user_data['User']["about"]);
                    $response["zip"] = trim($user_data['User']["zip"]);
                    if ($user_data['User']["role"] == 3) {
                        $response["provider_id"] = trim($user_data['Provider']["id"]);
                    }
                }
            } else {
                $response["message"] = "Invalid Email or Password.please try again";
            }
        }
        echo json_encode($response);
        die;
    }

    /**
     *
     * 2. For  Registration Page:-
     */
    public function register()
    {
        $data = file_get_contents('php://input');
        $req_data = json_decode($data, true);
        $response = array();
        $email = isset($req_data["email"]) ? $req_data["email"] : null;
        $first_name = isset($req_data["first_name"]) ? $req_data["first_name"] : null;
        $last_name = isset($req_data["last_name"]) ? $req_data["last_name"] : null;
        $password = isset($req_data["password"]) ? $req_data["password"] : null;
        $role = isset($req_data["role"]) ? $req_data["role"] : GUEST_ROLE;


        $response["status"] = 0;

        if (empty($password) || empty($email) || empty($first_name)) {
            $response["message"] = "Please fill all fields";
        } else {

            //Checking if email already exists
            $email_exists = $this->User->check_email($email);

            if ($email_exists) {
                $response["message"] = "Email address already in use!";
            } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $email_exists = true;
                $response["message"] = "Invalid email address !";
            }

            if ($email_exists != true) {

                $data = array();
                // $data['User']['id']= 10;
                $data['User']['email'] = $email;
                $data['User']['password'] = md5($password);
                $data['User']['first_name'] = $first_name;
                $data['User']['last_name'] = $last_name;
                $data['User']['register_source'] = 'app';
                $data['User']['role'] = $role;
                $data['User']['access_token'] = md5($data['User']['password'] . time());

                $register_success = false;

                $this->User->set($data);
                if ($this->User->validates()) {
                    $this->User->create();
                    $register_success = $this->User->save($data);

                    if ($role == AGENT_ROLE) {

                    }
                }


                if ($register_success) {
                    $userid = $this->User->id;
                    $response["status"] = 1;
                    $response["message"] = "Registration successful!";
                    $response["user_id"] = $userid;
                    $response["user_name"] = $first_name . " " . $last_name;
                    $response["role"] = $role;
                    $response["access_token"] = $data['User']['access_token'];
                } else {
                    $er = "";
                    $failed = $this->User->invalidFields();
                    if (!empty($failed)) {

                        foreach ($failed as $lf) {
                            $er .= "$lf[0]";
                            break;
                        }
                    }
                    $response['message'] = $er;
                }
            }
        }
        echo json_encode($response);
        die;
    }

    /*
     * 3. Forget password webservice:
     * */

    public function forgotpass()
    {
        $this->layout = false;

        // $req_data = $_POST;
        $data = file_get_contents('php://input');
        $req_data = json_decode($data, true);

        $response = array();
        $response["status"] = 0;
        $email = isset($req_data["email"]) ? $req_data["email"] : null;

        $users = $this->User->find('first', array('recursive' => -1, 'conditions' => array('User.email' => $email)));
        if (isset($users) && !empty($users) && $email) {

            if ($users['User']['status'] != 1) {
                if ($users['User']['status'] == 0) {
                    $response['message'] = "Your account has been blocked.Please contact to admin.";
                } else {
                    $response['message'] = "Your account not exist.Please contact to admin.";
                }
            } else {

                $id = $users['User']['id'];
                if ($users['User']['email'] == $email) {
                    $response['message'] = "Your password and further instruction has been send to your email address.Please Check your email.";
                    $response["status"] = 1;
                } else {
                    $response['message'] = "Your password and further instruction has been send to your email address.Please Check your email.";
                    $response["status"] = 1;
                }
            }
        } else {
            $response["status"] = 2;
            $response["message"] = empty($email) ? 'Email field is required.' : "Email is not exist in our database!";
        }

        echo json_encode($response);
        die;
    }

    // function for web service that post or not any thing
    function check_valid($fields_array)
    {
        $result = array();
        $result['status'] = 0;
        if ($this->request->is('post')) {
            if (!empty($this->request->data)) {
                if (isset($fields_array) && !empty($fields_array)) {
                    $fields_array_check = array();
                    foreach ($fields_array as $key => $value) {
                        $fields_array_check[$value] = isset($this->data[$value]) ? trim(strtolower($this->data[$value])) : "";
                    }
                }
                if (isset($fields_array_check) && !empty($fields_array_check)) {
                    $result['status'] = 1;
                    foreach ($fields_array_check as $key => $value) {
                        if ($value == "") {
                            $result['message'] = "Please fill all the fields";
                            $result['status'] = 0;
                            break;
                        }
                    }
                }
            } else {
                $result['message'] = "Please post some value";
            }
        } else {
            $result['message'] = "Invalid Request";
        }
        return $result;
    }


    // insert into reservation section and give suggesstion
    public function reservation_suggest()
    {
        $fields_array = array('currently_in', 'service_start_date_time', 'service_end_date_time', 'bookingtype', 'service_id', 'user_id', 'pickup_location', 'drop_off_location', 'travel_option');
        // check proper request is post or not in which all validation check
        $response = $this->check_valid($fields_array);
//        status == 1 means all thing is right other wise status ==0
        if ($response['status'] == 1) {
            $response['status'] == 0;
            $data = $this->request->data;
            $data['Reservation']['service_start_date_time'] = date("Y-m-d H:i:s", strtotime($data['service_start_date_time']));
            $data['Reservation']['service_end_date_time'] = date("Y-m-d H:i:s", strtotime($data['service_end_date_time']));
            // set pick up location check lat log that address is valiad or not
            $pick_up_lat_log_city = $this->DATA->Get_Lat_lng_city($data['pickup_location']);

            if ($pick_up_lat_log_city['status'] == "ok") {
                $data_reservation['Reservation']['pickup_loc_lat'] = $pick_up_lat_log_city['lat'];
                $data_reservation['Reservation']['pickup_loc_long'] = $pick_up_lat_log_city['lng'];
                $data_reservation['Reservation']['pick_up_location_city'] = $pick_up_lat_log_city['city_name'];


                $drop_off_lat_log_city = $this->DATA->Get_Lat_lng_city($data['drop_off_location']);

                if ($drop_off_lat_log_city['status'] == "ok") {

                    $data_reservation['Reservation']['drop_off_lat'] = $drop_off_lat_log_city['lat'];
                    $data_reservation['Reservation']['drop_off_long'] = $drop_off_lat_log_city['lng'];
                    $data_reservation['Reservation']['drop_off_location_city'] = $drop_off_lat_log_city['city_name'];

                    $data_reservation['Reservation']['currently_in'] = $data['currently_in'];
                    $data_reservation['Reservation']['estimated_hours'] = $this->DATA->calculate_hours($data['service_start_date_time'], $data['service_end_date_time']);


                    $data_reservation['Reservation']['service_id'] = $data['service_id'];
                    $data_reservation['Reservation']['user_id'] = $data['user_id'];
                    $data_reservation['Reservation']['drop_off_location'] = $data['drop_off_location'];
                    $data_reservation['Reservation']['pickup_location'] = $data['pickup_location'];
                    $data_reservation['Reservation']['travel_option'] = $data['travel_option'];
                    $data_reservation['Reservation']['bookingtype'] = $data['bookingtype'];


                    $data_reservation['Reservation']['service_start_date_time'] = date("Y-m-d H:i:s", strtotime($data['service_start_date_time']));
                    $data_reservation['Reservation']['service_end_date_time'] = date("Y-m-d H:i:s", strtotime($data['service_end_date_time']));


                    // find map estimated distance based on lat log
                    $distance = $this->DATA->get_driving_information($data['pickup_location'], $data['drop_off_location']);
                    $data_reservation['Reservation']['map_estimated_distance'] = $distance['distance'];
                    $data_reservation['Reservation']['map_estimated_hours'] = $distance['hours'];
                    $data_reservation['Reservation']['map_estimated_min'] = $distance['min'];
                    if (isset($data['vehicle_id']) && !empty($data['vehicle_id'])) {
                        $data_reservation['Reservation']['vehicle_id'] = $data['vehicle_id'];
                    }

                    $this->loadModel('Reservation');
                    if ($this->Reservation->save($data_reservation)) {
                        $response['status'] = 1;
                        $reservation_id = $this->Reservation->getLastInsertID();

                        $response['message'] = 'reservation saved successfully';
                        $response['reservation_id'] = $reservation_id;

                        // get listing of  provider for suggestion
                        $reservation = $this->Reservation->findById($reservation_id);
                        // $suggested_all_provider = $this->DATA->suggested_provider($reservation);

                        if ($reservation['Reservation']['service_id'] == 1) {
                            $suggested_all_provider = $this->DATA->suggested_provider($reservation);
                        } else if ($reservation['Reservation']['service_id'] == 2) {
                            // for taxi only two option  ....Only the per Hour and Per Kilometer should be the 2 options
                            $suggested_all_provider = $this->DATA->suggested_provider_for_taxi($reservation);
                        } else if ($reservation['Reservation']['service_id'] == 3) {
                            // for vehicle only two option  ....Only the per Hour and Per Day should be the 2 options.
                            $suggested_all_provider = $this->DATA->suggested_provider_for_vehicle($reservation);
                        }


                        if (isset($suggested_all_provider) && !empty($suggested_all_provider)) {
                            foreach ($suggested_all_provider as $key => $suggested_provider) {
                                $rating_array = array();
                                $rating_array['price'] = trim(number_format($suggested_provider['ServiceInformation']['rate'], 2));
                                $rating_array['provider_id'] = trim($suggested_provider['Provider']['id']);
                                $type = "";
                                if ($suggested_provider['ServiceInformation']['service_type_id'] == 1) {
                                    $type = "Per KM";
                                } else if ($suggested_provider['ServiceInformation']['service_type_id'] == 2) {
                                    $type = "Per Hour";
                                } else if ($suggested_provider['ServiceInformation']['service_type_id'] == 3) {
                                    $type = "Per Day";
                                }
                                $rating_array['type'] = trim($type);
                                $data_listing[] = $rating_array;
                            }
                            $response['data'] = $data_listing;
                        } else {
                            $this->Reservation->delete($reservation_id);
                            $response['status'] = 0;
                            $response['message'] = "Trilongo is not available in your area. please remember to try us out when you're in a trilongo city. Check out trilongo.com in the meantime";
                        }
                    } else {
                        $response['message'] = 'Sorry something went wrong.Please try again';
                    }
                } else {
                    $response['message'] = 'Sorry your Drop off location could not found on the map.Please fill valid address';
                }
            } else {
                $response['message'] = 'Sorry your pick up location could not found on the map.Please fill valid address';
            }
        }
        echo json_encode($response);
        die;
    }


    // reservation put price
    public function reservation_put_price()
    {
        $fields_array = array('price', 'reservation_id', 'communication_option');
        // check proper request is post or not in which all validation check
        $response = $this->check_valid($fields_array);
//        status == 1 means all thing is right other wise status ==0
        if ($response['status'] == 1) {
            $response['status'] = 0;
            $data = $this->request->data;
            $data_reservation['Reservation']['communication_option'] = $data['communication_option'];
            $data_reservation['Reservation']['your_price'] = $data['price'];
            // calculated total amount
            $this->loadModel('Reservation');
            $reservation = $this->Reservation->findById($data['reservation_id']);
            $your_total_price_type = $this->DATA->calculated_price_type_put_price($reservation, $data['price']);
            $data_reservation['Reservation']['your_total_price'] = $your_total_price_type[0];
            $data_reservation['Reservation']['your_price_type'] = $your_total_price_type[1];
            $data_reservation['Reservation']['id'] = $data['reservation_id'];
            $this->loadModel('Reservation');
            if ($this->Reservation->save($data_reservation)) {
                // board casting for this reservation
                $response_boardcast = $this->boardcast($data['reservation_id']);

                if ($response_boardcast['status'] == 1) {
                    $response['status'] = 1;
                    // set session for thank you page for reservation
                    $response['message'] = 'Cross your fingers and wait for Providers to accept your price';
                } else {
                    $response['message'] = $response_boardcast['message'];
                }
            } else {
                $response['message'] = 'Sorry something went wrong.Please try again';
            }
        }
        echo json_encode($response);
        die;
    }


    private function boardcast($rid = NULL)
    {
        $this->autoRender = false;
        $result = array('status' => '0');
        $reservation = $this->Reservation->findById($rid);
        if (!empty($reservation)) {
            // fetch all provider in our database
            $loadAllProviders = $this->User->find('all', array('conditions' => array('User.role' => '3', 'User.status' => '1')));
            if (isset($loadAllProviders) && !empty($loadAllProviders)) {
                //SMS ==1 email==2 both ==3
                if ($reservation['Reservation']['communication_option'] == 1) {
                    $this->BoardCastReservationSMS($reservation, $loadAllProviders);
                } else if ($reservation['Reservation']['communication_option'] == 2) {
                    $this->BoardCastReservationEmail($reservation, $loadAllProviders);
                } else if ($reservation['Reservation']['communication_option'] == 3) {
                    $this->BoardCastReservationEmail($reservation, $loadAllProviders);
                    // $this->BoardCastReservationSMS($reservation, $loadAllProviders);
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


    function insert_message($reservation, $subject, $message, $message_type)
    {
        $this->loadModel('Message');
        $this->loadModel('MessageIndex');
        return $message_id = $this->Message->Add($subject, $message, $reservation['Reservation']['user_id'], $reservation['Reservation']['id'], $message_type);
    }

    private function BoardCastReservationSMS($reservation, $Providers)
    {
        $subject = "New offer is received";
        $message = "One rider is looking for services";
        $message_id = $this->insert_message($reservation, $subject, $message, '1');
        foreach ($Providers as $User) {
            $arr = array();
            // if hire driver then get hire driver template and send email
            if ($reservation['Reservation']['service_id'] == 1) {
                // save in message index table with message id
                $this->MessageIndex->send($message_id, $message_id, $User['User']['id'], '1');
            } else if ($reservation['Reservation']['service_id'] == 2) {
                // save in message index table with message id
                $this->MessageIndex->send($message_id, $message_id, $User['User']['id'], '1');
            } else if ($reservation['Reservation']['service_id'] == 3) {
                // save in message index table with message id
                $this->MessageIndex->send($message_id, $message_id, $User['User']['id'], '1');
            }
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

    // update rider profile
    public function update_rider_profile()
    {
//        pr($this->request->data);die;

        $fields_array = array('user_id', 'first_name', 'last_name', 'gender', 'phone', 'country_name', 'country_id', 'state_name', 'state_id', 'city', 'zip', 'address', 'about');
        // check proper request is post or not in which all validation check
        $response = $this->check_valid($fields_array);
//         image is coming or not
        $files_array = $_FILES;
//        status == 1 means all thing is right other wise status ==0
        if ($response['status'] == 1) {
            $data = $this->request->data;
            // check image is set or not
            if (isset($files_array) && !empty($files_array) && $files_array['profile_pic']['error'] == 0) {
                $profile_image_name = $this->DATA->move_photo($files_array['profile_pic'], 'profile_photo');
                $data_user['User']['profile_pic'] = $profile_image_name;
                // check old image is exist or not if exist then unlink
                $old_image = WWW_ROOT . "data/profile_photo/" . $data['old_profile_pic'];
                if (!empty($provider_info['User']['profile_pic']) && file_exists($old_image)) {
                    unlink($old_image);
                }
            }
            $data_user['User']['id'] = $data['user_id'];
            $data_user['User']['first_name'] = $data['first_name'];
            $data_user['User']['last_name'] = $data['last_name'];
            $data_user['User']['gender'] = $data['gender'];
            $data_user['User']['phone'] = trim($data['phone']);
            $data_user['User']['country_name'] = $data['country_name'];
            $data_user['User']['country_id'] = $data['country_id'];
            $data_user['User']['state_name'] = $data['state_name'];
            $data_user['User']['state_id'] = $data['state_id'];
            $data_user['User']['city'] = $data['city'];
            $data_user['User']['zip'] = $data['zip'];
            $data_user['User']['address'] = $data['address'];
            $data_user['User']['about'] = $data['about'];
            $this->loadModel('User');
            if ($this->User->save($data_user)) {
                $response['status'] == 1;
                $response['message'] = 'User information updated successfully';
                $response['user_id'] = trim($data['user_id']);
                $response['first_name'] = trim($data['first_name']);
                $response['last_name'] = trim($data['last_name']);
                $response['gender'] = trim($data['gender']);
                $response['phone'] = trim($data['phone']);
                $response['country_name'] = trim($data['country_name']);
                $response['country_id'] = trim($data['country_id']);
                $response['state_name'] = trim($data['state_name']);
                $response['state_id'] = trim($data['state_id']);
                $response['city'] = trim($data['city']);
                $response['zip'] = trim($data['zip']);
                $response['address'] = trim($data['address']);
                $response['about'] = trim($data['about']);
                if (isset($profile_image_name)) {
                    $response["profile_image"] = trim($profile_image_name);
                }
            } else {
                $response['status'] = 0;
                $er = "";
                $failed = $this->User->invalidFields();
                if (!empty($failed)) {
                    foreach ($failed as $lf) {
                        $er .= "$lf[0]";
                        break;
                    }
                }
                $response['message'] = $er;
            }
        }
        echo json_encode($response);
        die;
    }

    // update rider profile
    public function update_provider_profile()
    {

        $fields_array = array('user_id', 'first_name', 'last_name', 'gender', 'phone', 'country_name', 'country_id', 'state_name', 'state_id', 'city', 'zip', 'address', 'about');
        // check proper request is post or not in which all validation check
        $response = $this->check_valid($fields_array);
//         image is coming or not
        $files_array = $_FILES;
//        status == 1 means all thing is right other wise status ==0
        if ($response['status'] == 1) {
            $response['status'] = 0;
            $data = $this->request->data;
            // check image is set or not
            if (isset($files_array) && !empty($files_array) && $files_array['profile_pic']['error'] == 0) {
                $profile_image_name = $this->DATA->move_photo($files_array['profile_pic'], 'profile_photo');
                $data_user['User']['profile_pic'] = $profile_image_name;
                // check old image is exist or not if exist then unlink
                $old_image = WWW_ROOT . "data/profile_photo/" . $data['old_profile_pic'];
                if (!empty($provider_info['User']['profile_pic']) && file_exists($old_image)) {
                    unlink($old_image);
                }
            }
            $data_user['User']['id'] = $data['user_id'];
            $data_user['User']['first_name'] = $data['first_name'];
            $data_user['User']['last_name'] = $data['last_name'];
            $data_user['User']['gender'] = $data['gender'];
            $data_user['User']['phone'] = $data['phone'];
            $data_user['User']['country_name'] = $data['country_name'];
            $data_user['User']['country_id'] = $data['country_id'];
            $data_user['User']['state_name'] = $data['state_name'];
            $data_user['User']['state_id'] = $data['state_id'];
            $data_user['User']['city'] = $data['city'];
            $data_user['User']['zip'] = $data['zip'];
            $data_user['User']['address'] = $data['address'];
            $data_user['User']['about'] = $data['about'];
            $this->loadModel('User');
            if ($this->User->save($data_user)) {
                $response['status'] = 1;
                $response['message'] = 'User information updated successfully';
                $response['user_id'] = trim($data['user_id']);
                $response['first_name'] = trim($data['first_name']);
                $response['last_name'] = trim($data['last_name']);
                $response['gender'] = trim($data['gender']);
                $response['phone'] = trim($data['phone']);
                $response['country_name'] = trim($data['country_name']);
                $response['country_id'] = trim($data['country_id']);
                $response['state_name'] = trim($data['state_name']);
                $response['state_id'] = trim($data['state_id']);
                $response['city'] = trim($data['city']);
                $response['zip'] = trim($data['zip']);
                $response['address'] = trim($data['address']);
                $response['about'] = trim($data['about']);
                if (isset($profile_image_name)) {
                    $response["profile_image"] = trim($profile_image_name);
                }
            } else {
                $response['status'] = 0;
                $er = "";
                $failed = $this->User->invalidFields();
                if (!empty($failed)) {
                    foreach ($failed as $lf) {
                        $er .= "$lf[0]";
                        break;
                    }
                }
                $response['message'] = $er;
            }
        }
        echo json_encode($response);
        die;
    }


    // update notification and reservation counting
    public function notification_reservation_count()
    {

        $fields_array = array('user_id');
        // check proper request is post or not in which all validation check
        $response = $this->check_valid($fields_array);
//        status == 1 means all thing is right other wise status ==0
        if ($response['status'] == 1) {
            $response['status'] = 0;
            $data = $this->request->data;
            // count total reservation counting
            $conditions = array('conditions' => array('Reservation.user_id' => $data['user_id'], 'OR' =>
                array(
                    array('Reservation.is_payment_complete' => 1, 'Reservation.provider_id <>' => 0),
                    array('Reservation.your_price <>' => 0)
                ),
            ));
            $this->loadModel('Reservation');
            $total_reservation = $this->Reservation->find('count', $conditions);
            $response["total_reservation"] = trim($total_reservation);
            $unread_notification = $this->DATA->getUnreadMessage($data['user_id']);
            $response["unread_notification"] = trim($unread_notification);

            $response['status'] = 1;
            $response['message'] = 'successfully';
        }
        echo json_encode($response);
        die;
    }


    // update contact information when user make a reservation
    public function contact_information_reservation()
    {
        $fields_array = array('reservation_id', 'city', 'phone_number', 'country_name', 'country_id', 'state_name', 'state_id');
        // check proper request is post or not in which all validation check
        $response = $this->check_valid($fields_array);
//        status == 1 means all thing is right other wise status ==0
        if ($response['status'] == 1) {
            $response['status'] = 0;
            $data = $this->request->data;
            $data_user['Reservation']['id'] = $data['reservation_id'];
            $data_user['Reservation']['phone_number'] = $data['phone_number'];
            $data_user['Reservation']['country_name'] = $data['country_name'];
            $data_user['Reservation']['country_id'] = $data['country_id'];
            $data_user['Reservation']['state_name'] = $data['state_name'];
            $data_user['Reservation']['state_id'] = $data['state_id'];
            $data_user['Reservation']['city'] = $data['city'];
            if (isset($data['address']) && !empty($data['address'])) {
                $data_user['Reservation']['address'] = $data['address'];
            }

            $this->loadModel('Reservation');
            if ($this->Reservation->save($data_user)) {
                $response['status'] = 1;
                $response['message'] = 'Reservation information updated successfully';
            } else {
                $response['message'] = 'Sorry something went wrong.Please try again';
            }
        }
        echo json_encode($response);
        die;
    }


    // choose provider

    public function choose_provider()
    {
        $fields_array = array('reservation_id', 'provider_id', 'communication_option');
        // check proper request is post or not in which all validation check
        $response = $this->check_valid($fields_array);
//        status == 1 means all thing is right other wise status ==0
        if ($response['status'] == 1) {
            $response['status'] = 0;
            $data = $this->request->data;
            $this->loadModel('Reservation');

            $reservation = $this->Reservation->find('first', array('conditions' => array('Reservation.id' => $data['reservation_id']), 'recursive' => 2));
            //  pr($reservation);die;
            if (isset($reservation) && !empty($reservation)) {
                $data_user['Reservation']['id'] = $data['reservation_id'];
                $data_user['Reservation']['provider_id'] = $data['provider_id'];
                // calculated total amount
                $total_cost = $this->DATA->calculated_price($reservation, $data['provider_id']);
                $trilongo_fee = number_format($total_cost * TRILONGO_PER_FEE, 2, '.', '');
                $total_amount = number_format(($total_cost + $trilongo_fee), 2, '.', '');
                $data_user['Reservation']['total_amount'] = $total_amount;
                $data_user['Reservation']['service_charge'] = $trilongo_fee;
                $data_user['Reservation']['provider_show_amount'] = $total_cost;
                $data_user['Reservation']['communication_option'] = $data['communication_option'];
                $this->loadModel('Reservation');
                if ($this->Reservation->save($data_user)) {
                    $response['status'] = 1;
                    $response['message'] = 'Reservation information updated successfully';
                    $response['payment'] = $total_cost;
                    $response['trilongo_fee'] = $trilongo_fee;
                    $response['total_payment'] = $total_amount;
                } else {
                    $response['message'] = 'Sorry something went wrong.Please try again';
                }

            } else {
                $response['message'] = 'Sorry reservation is not found';
            }
        }
        echo json_encode($response);
        die;
    }



    // make payment via paypal
    // update contact information when user make a reservation
    public function make_payment()
    {
        $fields_array = array('reservation_id', 'card_no', 'card_type', 'card_month', 'card_year', 'user_fname', 'user_lname', 'card_cvv');
        // check proper request is post or not in which all validation check
        $response = $this->check_valid($fields_array);
//        status == 1 means all thing is right other wise status ==0
        if ($response['status'] == 1) {
            $response['status'] = 0;
            $data = $this->request->data;
            // pr($data);die;
            $this->loadModel('Reservation');
            $this->loadModel('Provider');
            $this->Reservation->bindModel(array('belongsTo' => array('Provider')));
            $this->Provider->bindModel(array('belongsTo' => array('User')));
            $reservation = $this->Reservation->find('first', array('conditions' => array('Reservation.id' => $data['reservation_id']), 'recursive' => 2));


            if (isset($reservation) && !empty($reservation)) {
                if (isset($reservation['Reservation']['is_payment_complete']) && $reservation['Reservation']['is_payment_complete'] == 0) {
                    if ((isset($reservation['Reservation']['provider_id']) && $reservation['Reservation']['provider_id'] === "0") || empty($reservation['Reservation']['provider_id'])) {
                        $response['message'] = 'Please choose provider';
                    } else if ((isset($reservation['Reservation']['total_amount']) && $reservation['Reservation']['total_amount'] === "0") || empty($reservation['Reservation']['total_amount'])) {
                        $response['message'] = 'Sorry total amount is not choose.please try again';
                    } else {

                        $response_payment = $this->__process_reservation_pay_step2($reservation);
                        if ($response_payment['status'] == 1) {
                            $response['status'] = 1;
                            $response['message'] = "Payment done successfully";


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


                            // provider information
                            if (isset($reservation['Provider']['User']) && !empty($reservation['Provider']['User'])) {
                                $response["user_id"] = $reservation['Provider']['User']["id"];
                                $response["first_name"] = $reservation['Provider']['User']["first_name"];
                                $response["last_name"] = $reservation['Provider']['User']["last_name"];
                                $response["role"] = $reservation['Provider']['User']["role"];
                                $response["profile_image"] = $reservation['Provider']['User']["profile_pic"];
                                $response["phone_number"] = $reservation['Provider']['User']["phone"];
                                $response["country_name"] = $reservation['Provider']['User']["country_name"];
                                $response["country_id"] = $reservation['Provider']['User']["country_id"];
                                $response["state_name"] = $reservation['Provider']['User']["state_name"];
                                $response["city"] = $reservation['Provider']['User']["city"];
                                $response["address"] = $reservation['Provider']['User']["address"];
                                $response["gender"] = $reservation['Provider']['User']["gender"];
                                $response["about"] = $reservation['Provider']['User']["about"];
                                $response["zip"] = $reservation['Provider']['User']["zip"];
                            }

                        } else {
                            $response['status'] = 0;
                            $response['message'] = $response_payment['message'];
                        }
                    }
                } else {
                    $response['message'] = 'Payment is already done';
                }
            } else {
                $response['message'] = 'Sorry reservation is not found';
            }
        }
        echo json_encode($response);
        die;
    }


    private function __process_reservation_pay_step2($reservation)
    {

        $pay_err_msg = "";
        $errors = array();
        $user_id = $reservation['Reservation']['user_id'];
        $errors['status'] = 0;
        if ($this->request->is('post')) {
            $this->loadModel('PaymentLog');
            $this->PaymentLog->set($this->request->data);
            if ($this->PaymentLog->validates()) {
                $cardType = trim($this->request->data['card_type']);
                $cardNo = $this->request->data['card_no'];
                $expMonth = $this->request->data['card_month'];
                $expYear = $this->request->data['card_year'];
                $cvv2 = $this->request->data['card_cvv'];
                $uFname = $this->request->data['user_fname'];
                $uLname = $this->request->data['user_lname'];
                $trilongo_fee = $reservation['Reservation']['total_amount'] * TRILONGO_PER_FEE;

                $total_amount = number_format($reservation['Reservation']['total_amount'] + $trilongo_fee, 2);

                $curr_code = "USD";

                $doDirectPaymentResponse = $this->Paypal->DoDirect($cardType, $cardNo, $expMonth, $expYear, $cvv2, $uFname, $uLname, $total_amount, $curr_code);


                $pay_status = '';
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
                            "trilongo_fee" => $trilongo_fee,
                            "reservation_id" => $reservation['Reservation']['id'],
                            "status" => $pay_status,
                            "transaction_id" => $transaction_id,
                            "failure_reason" => $pay_err_msg,
                            "payment_for" => 0
                        )
                    );
                    $this->PaymentLog->save($this->request->data);
                    // set reservation table that payment is done for this reservation

                    $this->Reservation->id = $reservation['Reservation']['id'];
                    $this->Reservation->set('is_payment_complete', true);
                    $this->Reservation->save();
                    // create two entry in transaction table for one is for
                    $this->__create_escrow_transaction($user_id, $reservation);

                    $message_id = $this->DATA->send_message('payment_complete', 2, $reservation['Reservation']['user_id'], $reservation['Reservation']['id'], array());
                    $this->loadModel('MessageIndex');
                    $this->MessageIndex->send($message_id, $message_id, $reservation['Reservation']['user_id'], '2');

                    // second for provider that you are select for this reservation
                    // if it's not a proposal payment
                    if ($reservation['Reservation']['provider_status'] == 0) {
                        $message_id = $this->DATA->send_message('provider_received_new_reservation', 3, $reservation['Provider']['User']['id'], $reservation['Reservation']['id'], array());
                        $this->MessageIndex->send($message_id, $message_id, $reservation['Provider']['User']['id'], '3');
                    }
                    $errors['status'] = 1;
                    $response_payment['message'] = "done";

                } else {
                    $errors['message'] = $pay_err_msg;
                }
            } else {
                $errors_validates = $this->PaymentLog->validationErrors;
                foreach ($errors_validates as $key => $value) {
                    $errors['message'] = $value;
                }

            }

        }
        return $errors;


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


    // function for provider information
    public function provider_info()
    {
        $fields_array = array('provider_id');
        // check proper request is post or not in which all validation check
        $response = $this->check_valid($fields_array);
//        status == 1 means all thing is right other wise status ==0
        if ($response['status'] == 1) {
            $data = $this->request->data;
            $this->loadModel('Provider');
            $this->Provider->bindModel(array('belongsTo' => array('User')));
            $provider_info = $this->Provider->find('first', array('conditions' => array('Provider.id' => $data['provider_id']), 'recursive' => 2));

            if (isset($provider_info) && !empty($provider_info)) {
                $response["status"] = 1;
                $response["message"] = "successfully";
                $response["user_id"] = $provider_info['User']["id"];
                $response["first_name"] = $provider_info['User']["first_name"];
                $response["last_name"] = $provider_info['User']["last_name"];
                $response["role"] = $provider_info['User']["role"];
                $response["profile_image"] = $provider_info['User']["profile_pic"];
                $response["phone_number"] = $provider_info['User']["phone"];
                $response["country_name"] = $provider_info['User']["country_name"];
                $response["country_id"] = $provider_info['User']["country_id"];
                $response["state_name"] = $provider_info['User']["state_name"];
                $response["city"] = $provider_info['User']["city"];
                $response["address"] = $provider_info['User']["address"];
                $response["gender"] = $provider_info['User']["gender"];
                $response["about"] = $provider_info['User']["about"];
                $response["zip"] = $provider_info['User']["zip"];
            } else {
                $response['message'] = 'Sorry provider info is not found';
            }
        }
        echo json_encode($response);
        die;
    }

    // fetch all reservation based on user id
    public function rider_reservation_listing()
    {
        $fields_array = array('user_id');
        // check proper request is post or not in which all validation check
        $response = $this->check_valid($fields_array);
//        status == 1 means all thing is right other wise status ==0
        if ($response['status'] == 1) {

            $data = $this->request->data;
            $limit = isset($data['limit']) ? trim($data['limit']) : "10";
            $last_id = isset($data['last_id']) ? trim($data['last_id']) : "0";
            $response['next'] = 0;
            $response['status'] = 0;
            $this->loadModel('Reservation');
            $conditions = array('conditions' => array('Reservation.user_id' => $data['user_id'], 'OR' =>
                array(
                    array('Reservation.is_payment_complete' => 1, 'Reservation.provider_id <>' => 0),
                    array('Reservation.your_price <>' => 0)
                ),
                'Reservation.id >' => $last_id), 'limit' => $limit + 1, 'order' => array('Reservation.id' => 'ASC'));
            $this->Reservation->unbindModelAll();
            $all_reservation_data = $this->Reservation->find('all', $conditions);
            if (isset($all_reservation_data) && !empty($all_reservation_data)) {
                $total_reservation = count($all_reservation_data);
                if ($limit < $total_reservation) {
                    $response['next'] = 1;
                    unset($all_reservation_data[$total_reservation - 1]);
                }
                foreach ($all_reservation_data as $key => $reservation_data) {
                    $reservation_array = array();
                    $reservation_array['reservation_id'] = trim($reservation_data['Reservation']['id']);
                    $reservation_array['pickup_location'] = trim($reservation_data['Reservation']['pickup_location']);
                    $reservation_array['drop_of_location'] = trim($reservation_data['Reservation']['drop_off_location']);
                    $reservation_array['created'] = trim($reservation_data['Reservation']['created']);
                    if ($reservation_data['Reservation']['your_price'] != 0) {
                        $type = "put_price";
                    } else if ($reservation_data['Reservation']['provider_id'] != 0) {
                        $type = "choose_provider";
                    }
                    $reservation_array['type'] = trim($type);
                    $data_listing[] = $reservation_array;
                }
                $response['status'] = 1;
                $response['data'] = $data_listing;
            } else {
                $response['message'] = 'Sorry no reservation found';
            }
        }
        echo json_encode($response);
        die;
    }


    // fetch all message based on user id
    public function rider_reservation_put_price_more_info()
    {
        $fields_array = array('reservation_id');
        // check proper request is post or not in which all validation check
        $response = $this->check_valid($fields_array);
//        status == 1 means all thing is right other wise status ==0
        if ($response['status'] == 1) {
            $data = $this->request->data;
            $response['status'] = 0;
            $this->loadModel('Reservation');
            $conditions = array('conditions' => array('Reservation.id' => $data['reservation_id']));
            $this->Reservation->unbindModelAll();
            $this->Reservation->bindModel(array('hasMany' => array('Proposal' => array('conditions' => array('Proposal.provider_status' => 1)))));
            $reservation_data = $this->Reservation->find('first', $conditions);
            if (isset($reservation_data) && !empty($reservation_data)) {
                $response["status"] = 1;
                $response["message"] = "successfully";
                $response["price"] = trim($reservation_data['Reservation']["your_price"] . " " . $reservation_data['Reservation']['your_price_type']);
                $response["your_total_price"] = trim($reservation_data['Reservation']["your_total_price"]);
                $response["service_start_date_Time"] = trim(date("Y-m-d h:i A", strtotime($reservation_data['Reservation']['service_start_date_time'])));
                $response["service_end_date_Time"] = trim(date("Y-m-d h:i A", strtotime($reservation_data['Reservation']['service_end_date_time'])));
                $response["pick_up_location"] = trim($reservation_data['Reservation']['pickup_location']);
                $response["drop_off_location"] = trim($reservation_data['Reservation']['drop_off_location']);

                if (empty($reservation_data['Reservation']['provider_id'])) {
                    $response["job_status"] = 1; // job not awarded
                } else if (!empty($reservation_data['Reservation']['provider_id']) && $reservation_data['Reservation']['is_payment_complete'] != 1) {
                    $response["job_status"] = 2; //job only awarded but payment is not done
                } elseif (!empty($reservation_data['Reservation']['provider_id']) && $reservation_data['Reservation']['is_payment_complete'] == 1) {
                    $response["job_status"] = 3; // payment done and also awarded
                }
                // proposal listing find ... and show detail
                if (isset($reservation_data['Proposal']) && !empty($reservation_data['Proposal'])) {
                    foreach ($reservation_data['Proposal'] as $key => $value) {
                        $response['proposal_list'] = array();
                        $response['proposal_list']['id'] = $value['id'];
                        $response['proposal_list']['amount_type'] = $value['amount'] . " " . $value['price_type'];
                        $response['proposal_list']['total_amount'] = $value['total_price'];
                        $response['proposal_list']['provider_id'] = $value['provider_id'];
                        if ($reservation_data['Reservation']['provider_id'] == $value['provider_id']) {
                            $response['proposal_list']['awarded_status'] = 1;
                        } else {
                            $response['proposal_list']['awarded_status'] = 0;
                        }
                        $proposal_list[] = $response['proposal_list'];
                    }
                    $response['proposal_list'] = $proposal_list;
                } else {
                    $response['proposal_list'] = array();
                    $response["job_status"] = 0;
                }

            } else {
                $response['message'] = 'Sorry no reservation found';
            }
        }
        echo json_encode($response);
        die;
    }

    // fetch all message based on user id
    public function rider_reservation_choose_provider_more_info()
    {
        $fields_array = array('reservation_id');
        // check proper request is post or not in which all validation check
        $response = $this->check_valid($fields_array);
//        status == 1 means all thing is right other wise status ==0
        if ($response['status'] == 1) {
            $data = $this->request->data;
            $response['status'] = 0;
            $this->loadModel('Reservation');
            $this->loadModel('Provider');
            $conditions = array('recursive' => 2, 'conditions' => array('Reservation.id' => $data['reservation_id']));
            $this->Reservation->unbindModelAll();
            $this->Reservation->bindModel(array('belongsTo' => array('Provider')));
            $this->Provider->bindModel(array('belongsTo' => array('User')));
            $reservation_data = $this->Reservation->find('first', $conditions);
            if (isset($reservation_data) && !empty($reservation_data)) {
                $response["status"] = 1;
                $response["message"] = "successfully";
                $response["first_name"] = trim($reservation_data['Provider']['User']["first_name"]);
                $response["last_name"] = trim($reservation_data['Provider']['User']["last_name"]);
                $response["profile_image"] = trim($reservation_data['Provider']['User']["profile_pic"]);
                $response["phone_number"] = trim($reservation_data['Provider']['User']["phone"]);
                $response["country_name"] = trim($reservation_data['Provider']['User']["country_name"]);
                $response["country_id"] = trim($reservation_data['Provider']['User']["country_id"]);
                $response["state_name"] = trim($reservation_data['Provider']['User']["state_name"]);
                $response["state_id"] = trim($reservation_data['Provider']['User']["state_id"]);
                $response["city"] = trim($reservation_data['Provider']['User']["city"]);
                $response["address"] = trim($reservation_data['Provider']['User']["address"]);
                $response["gender"] = trim($reservation_data['Provider']['User']["gender"]);
                $response["about"] = trim($reservation_data['Provider']['User']["about"]);
                $response["zip"] = trim($reservation_data['Provider']['User']["zip"]);
                $current_status = "";
                if (isset($reservation_data['Reservation']['provider_status'])) {
                    if ($reservation_data['Reservation']['provider_status'] == 0) {
                        $current_status = "Pending";
                    } else if ($reservation_data['Reservation']['provider_status'] == 1) {
                        $current_status = "Accepted";
                    } else if ($reservation_data['Reservation']['provider_status'] == 2) {
                        $current_status = "Declined";
                    }
                }
                $response["current_status"] = trim($current_status);


                $response["price"] = trim($reservation_data['Reservation']["total_amount"]);
                $response["service_start_date_Time"] = trim(date("Y-m-d h:i A", strtotime($reservation_data['Reservation']['service_start_date_time'])));
                $response["service_end_date_Time"] = trim(date("Y-m-d h:i A", strtotime($reservation_data['Reservation']['service_end_date_time'])));
                $response["pick_up_location"] = trim($reservation_data['Reservation']['pickup_location']);
                $response["drop_off_location"] = trim($reservation_data['Reservation']['drop_off_location']);
            } else {
                $response['message'] = 'Sorry no reservation found';
            }
        }
        echo json_encode($response);
        die;
    }

    // fetch all message based on user id
    public function rider_message_listing()
    {
        $fields_array = array('user_id');
        // check proper request is post or not in which all validation check
        $response = $this->check_valid($fields_array);
//        status == 1 means all thing is right other wise status ==0
        if ($response['status'] == 1) {
            $data = $this->request->data;
            $limit = isset($data['limit']) ? trim($data['limit']) : "10";
            $last_id = isset($data['last_id']) ? trim($data['last_id']) : "0";
            $response['next'] = 0;
            $response['status'] = 0;
            $this->loadModel('MessageIndex');
            $this->MessageIndex->bindModel(array('belongsTo' => array('Message')));
            $conditions_last_id = "";
            if ($last_id > 0) {
                $conditions_last_id = array('MessageIndex.id <' => $last_id);
            }
            $conditions = array('conditions' => array('MessageIndex.recipient_id' => $data['user_id'],$conditions_last_id, 'MessageIndex.deleted' => 0), 'limit' => $limit + 1, 'order' => array('MessageIndex.id' => 'DESC'));
            $all_message_data = $this->MessageIndex->find('all', $conditions);

            if (isset($all_message_data) && !empty($all_message_data)) {
                $total_message = count($all_message_data);
                if ($limit < $total_message) {
                    $response['next'] = 1;
                    unset($all_message_data[$total_message - 1]);
                }
                foreach ($all_message_data as $key => $message_data) {
                    $message_array = array();
                    $message_array['message_id'] = trim($message_data['MessageIndex']['id']);
                    $message_array['subject'] = trim($message_data['Message']['subject']);
                    $message_array['message'] = trim($message_data['Message']['message']);
                    $message_array['created'] = trim(date("h:i:A Y-m-d", strtotime($message_data['MessageIndex']['created'])));
                    if ($message_data['MessageIndex']['is_read'] == 1) {
                        $type = "read";
                    } else {
                        $type = "un_read";
                    }
                    $message_array['type'] = trim($type);
                    $data_listing[] = $message_array;
                }
                $response['status'] = 1;
                $response['data'] = $data_listing;
            } else {
                $response['message'] = 'Sorry no message found';
            }
        }
        echo json_encode($response);
        die;
    }

    // fetch all message based on user id
    public function provider_message_listing()
    {
        $fields_array = array('user_id', 'provider_id');
        // check proper request is post or not in which all validation check
        $response = $this->check_valid($fields_array);
//        status == 1 means all thing is right other wise status ==0
        if ($response['status'] == 1) {
            $data = $this->request->data;
            $limit = isset($data['limit']) ? trim($data['limit']) : "10";
            $last_id = isset($data['last_id']) ? trim($data['last_id']) : "0";
            $response['next'] = 0;
            $response['status'] = 0;
            $this->loadModel('MessageIndex');
            $this->loadModel('Reservation');
            $this->MessageIndex->bindModel(array('belongsTo' => array('Message')));
            $this->MessageIndex->bindModel(array('hasOne' => array(
                'Reservation' => array('foreignKey' => false, 'conditions' => array('Reservation.id=Message.reservation_id'))
            )));
            $this->Reservation->unbindModelAll();
            $this->Reservation->bindModel(array('hasOne' => array(
                'Proposal' => array('conditions' => array('Proposal.provider_id' => $data['provider_id'])),
            )));
            $conditions_last_id = "";
            if ($last_id > 0) {
                $conditions_last_id = array('MessageIndex.id <' => $last_id);
            }
            $conditions = array('recursive' => 2, 'conditions' => array('MessageIndex.recipient_id' => $data['user_id'], 'MessageIndex.deleted' => 0, $conditions_last_id), 'limit' => $limit + 1, 'order' => array('MessageIndex.id' => 'DESC'));
            $all_message_data = $this->MessageIndex->find('all', $conditions);

            if (isset($all_message_data) && !empty($all_message_data)) {
                $total_message = count($all_message_data);
                if ($limit < $total_message) {
                    $response['next'] = 1;
                    unset($all_message_data[$total_message - 1]);
                }
                foreach ($all_message_data as $key => $message_data) {
                    $message_array = array();
                    $message_array['message_id'] = trim($message_data['MessageIndex']['id']);
                    $message_array['reservation_id'] = trim($message_data['Message']['reservation_id']);
                    $message_array['subject'] = trim($message_data['Message']['subject']);
                    $message_array['message'] = trim($message_data['Message']['message']);
                    $message_array['message_type'] = trim($message_data['MessageIndex']['message_type']);
                    $message_array['created'] = trim(date("h:i:A Y-m-d", strtotime($message_data['MessageIndex']['created'])));
                    if ($message_data['MessageIndex']['is_read'] == 1) {
                        $type = "read";
                    } else {
                        $type = "un_read";
                    }
                    $button_name = $status = "";
                    if ($message_data['MessageIndex']['message_type'] == 1) {
                        $button_name = " view_more_info";

                        if (isset($message_data['Reservation']['Proposal']) && !empty($message_data['Reservation']['Proposal'])) {
                            if (array_filter($message_data['Reservation']['Proposal'])) {
                                if ($message_data['Reservation']['Proposal']['provider_status'] == 0) {
                                    $status = 'Pending';
                                } else if ($message_data['Reservation']['Proposal']['provider_status'] == 1 && $message_data['Reservation']['Proposal']['rider_status'] == 0) {
                                    $status = 'Wait for Feedback';
                                } else if ($message_data['Reservation']['Proposal']['provider_status'] == 2) {
                                    $status = 'Declined';
                                } else if ($message_data['Reservation']['Proposal']['provider_status'] == 1 && $message_data['Reservation']['Proposal']['rider_status'] == 1) {
                                    $status = 'Awarded';
                                } else if ($message_data['Reservation']['Proposal']['provider_status'] == 1 && $message_data['Reservation']['Proposal']['rider_status'] == 2) {
                                    $status = 'Not Awarded';
                                }
                            } else {

                                if (!array_filter($message_data['Reservation']['Proposal'])) {
                                    $status = 'Pending';
                                }
                            }
                        } else {
                            $status = 'Pending';
                        }
                    } else if ($message_data['MessageIndex']['message_type'] == 2) {
                        $button_name = " delete";
                    } else if ($message_data['MessageIndex']['message_type'] == 3) {
                        $button_name = " view_more_info";
                        if ($message_data['Reservation']['provider_status'] == 0) {
                            $status = 'Pending';
                        } else if ($message_data['Reservation']['provider_status'] == 1) {
                            $status = 'Accepted';
                        } else if ($message_data['Reservation']['provider_status'] == 2) {
                            $status = 'Declined';
                        }
                    }
                    $message_array['status'] = trim($status);
                    $message_array['button_name'] = trim($button_name);
                    $message_array['type'] = trim($type);
                    $data_listing[] = $message_array;
                }
                $response['status'] = 1;
                $response['data'] = $data_listing;
            } else {
                $response['message'] = 'Sorry no message found';
            }
        }
        echo json_encode($response);
        die;
    }


    // delete message
    public function delete_message()
    {

        $fields_array = array('user_id', 'message_id');
        // check proper request is post or not in which all validation check
        $response = $this->check_valid($fields_array);
//        status == 1 means all thing is right other wise status ==0
        if ($response['status'] == 1) {
            $response['status'] = 0;
            $data = $this->request->data;
            // count total reservation counting
            $this->loadModel('MessageIndex');
            $message_check = $this->MessageIndex->find("first", array('conditions' => array('MessageIndex.id' => $data['message_id'], 'MessageIndex.recipient_id' => $data['user_id'], 'MessageIndex.deleted' => 0)));


            if (isset($message_check) && !empty($message_check)) {
                // update message index table
                $message_update = array();
                $message_update['MessageIndex']['id'] = $data['message_id'];
                $message_update['MessageIndex']['is_read'] = 1;
                $message_update['MessageIndex']['deleted'] = 1;
                if ($this->MessageIndex->save($message_update)) {
                    $response['message'] = "Message deleted successfully";
                    $response['status'] = 1;
                } else {
                    $response['message'] = "Sorry message is not updated";
                }
            } else {
                $response['message'] = "Sorry something went wrong.Please try again";
            }
        }
        echo json_encode($response);
        die;
    }

    // delete message
    public function read_message()
    {

        $fields_array = array('user_id', 'message_id');
        // check proper request is post or not in which all validation check
        $response = $this->check_valid($fields_array);
//        status == 1 means all thing is right other wise status ==0
        if ($response['status'] == 1) {
            $response['status'] = 0;
            $data = $this->request->data;

            $this->loadModel('MessageIndex');
            $message_check = $this->MessageIndex->find("first", array('conditions' => array('MessageIndex.id' => $data['message_id'], 'MessageIndex.recipient_id' => $data['user_id'], 'MessageIndex.is_read' => 0, 'MessageIndex.deleted' => 0)));
            if (isset($message_check) && !empty($message_check)) {
                // update message index table
                $message_update = array();
                $message_update['MessageIndex']['id'] = $data['message_id'];
                $message_update['MessageIndex']['is_read'] = 1;
                if ($this->MessageIndex->save($message_update)) {
                    $response['message'] = "Message read successfully";
                    $response['status'] = 1;
                } else {
                    $response['message'] = "Sorry message is not updated";
                }
            } else {
                $response['message'] = "Sorry something went wrong.Please try again";
            }
        }
        echo json_encode($response);
        die;
    }

    // more info for reservation
    // message_type==3
    public function provider_more_info_reservation()
    {
        $fields_array = array('reservation_id', 'provider_id');
        // check proper request is post or not in which all validation check
        $response = $this->check_valid($fields_array);
//        status == 1 means all thing is right other wise status ==0
        if ($response['status'] == 1) {
            $response['status'] = 0;
            $data = $this->request->data;
            $this->loadModel('Reservation');
            $this->Reservation->unbindModelAll();
            $reservation_data = $this->Reservation->find('first', array('conditions' => array('Reservation.id' => $data['reservation_id'], 'Reservation.provider_id' => $data['provider_id'])));
            if (isset($reservation_data) && !empty($reservation_data)) {
                $response["status"] = 1;
                $response["message"] = "successfully";
                $response["price"] = trim($reservation_data['Reservation']["total_amount"]);
                $response["service_start_date_Time"] = trim(date("Y-m-d h:i A", strtotime($reservation_data['Reservation']['service_start_date_time'])));
                $response["service_end_date_Time"] = trim(date("Y-m-d h:i A", strtotime($reservation_data['Reservation']['service_end_date_time'])));
                $response["pick_up_location"] = trim($reservation_data['Reservation']['pickup_location']);
                $response["drop_off_location"] = trim($reservation_data['Reservation']['drop_off_location']);
                if ($reservation_data['Reservation']['provider_status'] == 0) {
                    $response["accept"] = "Accept";
                    $response["decline"] = "Decline";
                    $response["button"] = 1;
                }

            } else {
                $response['message'] = "Sorry something went wrong.Please try again";
            }
        }
        echo json_encode($response);
        die;

    }
    // more info for reservation
    // message_type==1
    public function provider_offer_more_info_reservation()
    {
        $fields_array = array('reservation_id', 'provider_id');
        // check proper request is post or not in which all validation check
        $response = $this->check_valid($fields_array);
//        status == 1 means all thing is right other wise status ==0
        if ($response['status'] == 1) {
            $response['status'] = 0;
            $data = $this->request->data;
            $this->loadModel('Reservation');
            $this->Reservation->unbindModelAll();
            $this->Reservation->bindModel(array('hasOne' => array(
                'Proposal' => array('conditions' => array('Proposal.provider_id' => $data['provider_id'])),
            )));
            $reservation_data = $this->Reservation->find('first', array('recursive' => 2, 'conditions' => array('Reservation.id' => $data['reservation_id'])));
            if (isset($reservation_data) && !empty($reservation_data)) {
                $response["status"] = 1;
                $response["message"] = "successfully";
                $response["price"] = trim($reservation_data['Reservation']['your_price'] . " " . $reservation_data['Reservation']['your_price_type']);
                $response["total_price"] = trim($reservation_data['Reservation']["your_total_price"]);
                $response["service_start_date_Time"] = trim(date("Y-m-d h:i A", strtotime($reservation_data['Reservation']['service_start_date_time'])));
                $response["service_end_date_Time"] = trim(date("Y-m-d h:i A", strtotime($reservation_data['Reservation']['service_end_date_time'])));
                $response["pick_up_location"] = trim($reservation_data['Reservation']['pickup_location']);
                $response["drop_off_location"] = trim($reservation_data['Reservation']['drop_off_location']);

                if (array_filter($reservation_data['Proposal'])) {
                    if ($reservation_data['Proposal']['provider_status'] == 1) {
                        $response["rate"] = trim($reservation_data['Proposal']['amount'] . " " . $reservation_data['Proposal']['price_type']);
                        $response["total_proposal_price"] = trim($reservation_data['Proposal']['total_price']);
                    } elseif ($reservation_data['Proposal']['provider_status'] == 0) {
                        $response['message'] = "You reject this proposal";
                    }
                } else {
                    $response["accept"] = "Accept";
                    $response["decline"] = "Decline";
                    $response["button"] = 1;
                }

            } else {
                $response['message'] = "Sorry something went wrong.Please try again";
            }
        }
        echo json_encode($response);
        die;

    }


    // function for vehicle list
    public function vehicle_list()
    {
        $this->loadModel('VehicleType');
        $vehicle_type = $this->VehicleType->find('all', array('conditions' => array('VehicleType.status' => 1)));
        if (isset($vehicle_type) && !empty($vehicle_type)) {
            $response["status"] = 1;
            $response["message"] = "successfully";
            foreach ($vehicle_type as $value) {
                $vehicle_array = array();
                $vehicle_array["name"] = trim($value['VehicleType']["name"]);
                $vehicle_array["id"] = trim($value['VehicleType']["id"]);
                $data[] = $vehicle_array;
            }
            $response["data"] = $data;
        } else {
            $response['message'] = "Vehicle type is not found";
        }
        echo json_encode($response);
        die;
    }


// rider accept the reservation
    public function rider_accept_reservation()
    {
        $fields_array = array('reservation_id', 'user_id', 'proposal_id', 'provider_id');
        // check proper request is post or not in which all validation check
        $response = $this->check_valid($fields_array);
//        status == 1 means all thing is right other wise status ==0
        if ($response['status'] == 1) {
            $response['status'] = 0;
            $data = $this->request->data;


            $this->loadModel('Reservation');
            $this->loadModel('Proposal');

            $this->Reservation->bindModel(array('hasOne' => array('Proposal' => array('conditions' => array('Proposal.id' => $data['proposal_id'])))));
            $this->Proposal->bindModel(array('belongsTo' => array('Provider')));
            $reservation_data = $this->Reservation->find('first', array('recursive' => 2, 'conditions' => array('Reservation.id' => $data['reservation_id'], 'Reservation.user_id' => $data['user_id'])));
            if (isset($reservation_data) && !empty($reservation_data)) {
                $response["status"] = 1;
                $response["message"] = "successfully";
                $reservation_info = array();
                $reservation_info['Reservation']['provider_id'] = $data['provider_id'];
                $reservation_info['Reservation']['provider_status'] = 1;
                $trilongo_fee = number_format($reservation_data['Proposal']['total_price'] * TRILONGO_PER_FEE, 2, '.', '');
                $total_amount = number_format(($reservation_data['Proposal']['total_price'] + $trilongo_fee), 2, '.', '');
                $reservation_info['Reservation']['total_amount'] = $total_amount;
                $reservation_info['Reservation']['service_charge'] = $trilongo_fee;
                $reservation_info['Reservation']['provider_show_amount'] = $reservation_data['Proposal']['total_price'];
                $reservation_info['Reservation']['id'] = $data['reservation_id'];

                if ($this->Reservation->save($reservation_info)) {
                    $proposal_array = array();
                    $proposal_array['Proposal']['id'] = $data['proposal_id'];
                    $proposal_array['Proposal']['rider_status'] = 1;
                    $this->Proposal->save($proposal_array);

                    $message_id = $this->DATA->send_message('rider_choose_proposal', 2, $reservation_data['Proposal']['Provider']['user_id'], $reservation_data['Reservation']['id'], array());
                    $this->loadModel('MessageIndex');
                    $this->MessageIndex->send($message_id, $message_id, $reservation_data['Proposal']['Provider']['user_id'], '2');


                    $response['payment'] = $reservation_data['Proposal']['total_price'];
                    $trilongo_fee = number_format($reservation_data['Proposal']['total_price'] * TRILONGO_PER_FEE, 2);
                    $response['trilongo_fee'] = $trilongo_fee;
                    $response['total_payment'] = number_format($reservation_data['Proposal']['total_price'] + $trilongo_fee, 2);
                }


            } else {
                $response['message'] = "Sorry something went wrong.Please try again";
            }
        }
        echo json_encode($response);
        die;

    }


    // reservation show payment detail
    public function show_payment()
    {
        $fields_array = array('reservation_id', 'user_id');
        // check proper request is post or not in which all validation check
        $response = $this->check_valid($fields_array);
//        status == 1 means all thing is right other wise status ==0
        if ($response['status'] == 1) {
            $response['status'] = 0;
            $data = $this->request->data;
            $this->loadModel('Reservation');
            $reservation_data = $this->Reservation->find('first', array('recursive' => 2, 'conditions' => array('Reservation.id' => $data['reservation_id'], 'Reservation.user_id' => $data['user_id'])));
            if (isset($reservation_data) && !empty($reservation_data)) {
                $response["status"] = 1;
                $response["message"] = "successfully";
                $response['payment'] = $reservation_data['Reservation']['total_amount'];
                $trilongo_fee = number_format($reservation_data['Reservation']['total_amount'] * TRILONGO_PER_FEE, 2);
                $response['trilongo_fee'] = $trilongo_fee;
                $response['total_payment'] = number_format($reservation_data['Reservation']['total_amount'] + $trilongo_fee, 2);
            } else {
                $response['message'] = "Sorry something went wrong.Please try again";
            }
        }
        echo json_encode($response);
        die;
    }

    //provider accept the offer of reservation
    function provider_accept_offer_reservation()
    {
        $fields_array = array('reservation_id', 'user_id', 'provider_id', 'amount');
        // check proper request is post or not in which all validation check
        $response = $this->check_valid($fields_array);
//        status == 1 means all thing is right other wise status ==0
        if ($response['status'] == 1) {
            $response['status'] = 0;
            $data = $this->request->data;
            $this->loadModel('Message');
            $this->loadModel('Reservation');
            $this->Message->bindModel(array('belongsTo' => array('Reservation' => array('type' => 'INNER'))));
            $this->Message->bindModel(array('hasOne' => array('MessageIndex' => array('type' => 'INNER', 'conditions' => array('MessageIndex.recipient_id=' . $data['user_id'])))));
            $this->Reservation->unbindModelAll();
            $this->Reservation->bindModel(array('hasOne' => array(
                'Proposal' => array('conditions' => array('Proposal.provider_id' => $data['provider_id'])),
            )));
            $message_section = $this->Message->find('first', array('recursive' => 2, 'conditions' => array('Message.reservation_id' => $data['reservation_id'], 'Message.message_type' => 1, 'MessageIndex.recipient_id' => $data['user_id'])));
            if (isset($data['amount']) && is_numeric($data['amount'])) {
                if (!empty($message_section)) {
                    if (!array_filter($message_section['Reservation']['Proposal'])) {
                        $your_total_price_type = $this->DATA->calculated_price_type_put_price($message_section, $data['amount']);
                        $Proposal_data['Proposal']['reservation_id'] = $data['reservation_id'];
                        $Proposal_data['Proposal']['provider_id'] = $data['provider_id'];
                        $Proposal_data['Proposal']['message_id'] = $message_section['Message']['id'];
                        $Proposal_data['Proposal']['amount'] = number_format($data['amount'], 2, '.', '');
                        $Proposal_data['Proposal']['price_type'] = $message_section['Reservation']['your_price_type'];
                        $Proposal_data['Proposal']['total_price'] = number_format($your_total_price_type[0], 2, '.', '');
                        $Proposal_data['Proposal']['provider_status'] = 1;
                        $this->loadModel('Proposal');
                        $this->Proposal->save($Proposal_data);


                        // send message
                        $message_id = $this->DATA->send_message('reservation_accept_by_provider_put_price', 2, $message_section['Reservation']['user_id'], $message_section['Reservation']['id'], array());
                        $this->loadModel('MessageIndex');
                        $this->MessageIndex->send($message_id, $message_id, $message_section['Reservation']['user_id'], '2');

                        $response["status"] = 1;
                        $response["message"] = "Now you wait for feedback";
                    } else {
                        $response["message"] = "You have already submit proposal for this reservation";
                    }
                } else {
                    $response["message"] = "Sorry something went wrong.please try again";
                }
            } else {
                $response['message'] = "Please fill valid price";
            }


        }
        echo json_encode($response);
        die;
    }


    //provider accept the offer of reservation
    function provider_cancel_offer_reservation()
    {
        $fields_array = array('reservation_id', 'user_id', 'provider_id');
        // check proper request is post or not in which all validation check
        $response = $this->check_valid($fields_array);
//        status == 1 means all thing is right other wise status ==0
        if ($response['status'] == 1) {
            $response['status'] = 0;
            $data = $this->request->data;
            $this->loadModel('Message');
            $this->Message->bindModel(array('belongsTo' => array('Reservation' => array('type' => 'INNER'))));
            $this->Message->bindModel(array('hasOne' => array('Proposal' => array('conditions' => array('Proposal.provider_id' => $data['provider_id'])), 'MessageIndex' => array('type' => 'INNER', 'conditions' => array('MessageIndex.recipient_id=' . $data['user_id'])))));
            $message_section = $this->Message->find('first', array('conditions' => array('Message.reservation_id' => $data['reservation_id'], 'Message.message_type' => 1, 'MessageIndex.recipient_id' => $data['user_id'])));

            if (!empty($message_section)) {
                // check proposal already submitted or not
                if (!array_filter($message_section['Proposal'])) {
                    $Proposal_data['Proposal']['reservation_id'] = $data['reservation_id'];
                    $Proposal_data['Proposal']['provider_id'] = $data['provider_id'];
                    $Proposal_data['Proposal']['message_id'] = $message_section['Message']['id'];
                    $Proposal_data['Proposal']['proposed_amount'] = Null;
                    $Proposal_data['Proposal']['provider_status'] = 2;
                    $this->loadModel('Proposal');
                    $this->Proposal->save($Proposal_data);
                    // send message
                    $message_id = $this->DATA->send_message('reservation_cancel_by_provider_put_price', 2, $message_section['Reservation']['user_id'], $message_section['Reservation']['id'], array());
                    $this->loadModel('MessageIndex');
                    $this->MessageIndex->send($message_id, $message_id, $message_section['Reservation']['user_id'], '2');

                    $response["status"] = 1;
                    $response["message"] = "Sorry, I'm booked up";
                } else {
                    $response["message"] = "You have already submit proposal for this reservation";
                }

            } else {
                $response["message"] = "Sorry something went wrong.please try again";
            }
        }
        echo json_encode($response);
        die;
    }


    // function for provider accept or reject that reservation
    public function provider_accept_reject_reservation()
    {
        $fields_array = array('reservation_id', 'user_id', 'provider_id', 'status');
        // check proper request is post or not in which all validation check
        $response = $this->check_valid($fields_array);
//        status == 1 means all thing is right other wise status ==0
        if ($response['status'] == 1) {
            $response['status'] = 0;
            $data = $this->request->data;
            $this->loadModel('Reservation');
            $this->Reservation->unbindModelAll();
            $this->Reservation->bindModel(array('hasOne' => array('TransactionLog' => array('type' => 'INNER', 'conditions' => array('TransactionLog.payment_type' => 2)))));
            $reservation_data = $this->Reservation->find('first', array('conditions' => array('Reservation.id' => $data['reservation_id'], 'Reservation.provider_id' => $data['provider_id'], 'Reservation.provider_status' => 0)));
            if (!empty($reservation_data)) {
                // check proposal already submitted or not
                if ($data['status'] == 1) {
//means user want accept this reservation
//            update reservation table then update total balance then update payment log table
                    //1) update reservation table
                    $reservation_info['Reservation']['id'] = $data['reservation_id'];
                    $reservation_info['Reservation']['provider_status'] = 1;

                    if ($this->Reservation->save($reservation_info)) {
                        $this->loadModel('User');
                        $this->loadModel('PaymentLog');
                        //2) update total balance

                        if ($this->User->credit($data['user_id'], $reservation_data['TransactionLog']['amount'])) {
//                       3) update payment log table
                            $this->DATA->update_transaction_log($reservation_data['Reservation']['id'], $reservation_data['Reservation']['provider_id'], 1);
                            // provider accept the reservation


                            $message_id = $this->DATA->send_message('reservation_accept_by_provider_choose_provider', 2, $reservation_data['Reservation']['user_id'], $data['reservation_id'], array());
                            if ($message_id) {
                                $this->loadModel('MessageIndex');
                                $this->MessageIndex->send($message_id, $message_id, $reservation_data['Reservation']['user_id'], '2');
                            }
                            $message_id = $this->DATA->send_message('fund_added_your_account', 2, $reservation_data['Reservation']['user_id'], $data['reservation_id'], array());
                            if ($message_id) {
                                $this->loadModel('MessageIndex');
                                $this->MessageIndex->send($message_id, $message_id, $data['user_id'], '2');
                            }
                        }
                        $response["status"] = 1;
                        $response["message"] = "Reservation is accepted by you";
                    } else {
                        $response["message"] = "Sorry reservation is updated.Please try again";
                    }
                } else if ($data['status'] == 0) {
                    //means user want accept this reservation
                    //1) update reservation table
                    $reservation_info['Reservation']['id'] = $data['reservation_id'];
                    $reservation_info['Reservation']['provider_status'] = 2;
                    $this->Reservation->save($reservation_info);


                    //  2) update payment log table
                    $this->DATA->update_transaction_log($reservation_data['Reservation']['id'], $reservation_data['Reservation']['provider_id'], 2);


                    $message_id = $this->DATA->send_message('reservation_cancel_by_provider_choose_provider', 2, $reservation_data['Reservation']['user_id'], $data['reservation_id'], array());
                    $this->loadModel('MessageIndex');
                    $this->MessageIndex->send($message_id, $message_id, $reservation_data['Reservation']['user_id'], '2');
                    $response["message"] = "Reservation is canceled";
                    $response["status"] = 1;
                }

            } else {
                $response["message"] = "Sorry something went wrong.please try again";
            }
        }
        echo json_encode($response);
        die;
    }

    // for static page
    public function page()
    {
        $fields_array = array('url');
        // check proper request is post or not in which all validation check
        $response = $this->check_valid($fields_array);
//        status == 1 means all thing is right other wise status ==0
        if ($response['status'] == 1) {
            $response['status'] = 0;
            $data = $this->request->data;
            $this->loadModel('Page');
            $page_data = $this->Page->find('first', array('conditions' => array('Page.url' => $data['url'], 'Page.status' => 1)));
            if ($page_data) {
                $response['description'] = trim($page_data['Page']['post_data']);
                $response['message'] = "success";
                $response['status'] = 1;
            } else {
                $response['message'] = "Sorry no page associated with this url";
            }
        }
        echo json_encode($response);
        die;
    }


}
