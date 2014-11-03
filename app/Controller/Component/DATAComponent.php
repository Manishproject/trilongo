<?php

class DATAComponent extends Component
{

    public $components = array('Cookie', 'Session', 'Email', 'Security', 'DATA', 'Paginator');

    public function AppMail($to, $type, $parameters = array())
    {
        $mdata = ClassRegistry::init('Mail');
        $today_date = date("j F, Y", strtotime(TODAYDATE));
        $today_year = date("Y");
        $arr = array();
        $emailformat = $mdata->find('first', array('conditions' => array('Mail.type' => $type)));
        if (!empty($emailformat)) {
            $sub = $emailformat['Mail']['subject'];
            foreach ($parameters as $param_name => $param_value) {
                $sub = str_replace('[' . $param_name . ']', $param_value, $sub);
            }

            $body = str_replace('[DATE]', $today_date, $emailformat['Mail']['message']);
            $body = str_replace('[CYEAR]', $today_year, $body);
            foreach ($parameters as $param_name => $param_value) {
                $body = str_replace("[$param_name]", $param_value, $body);
            }
            $from = $emailformat['Mail']['sender_name'] . "<" . $emailformat['Mail']['email'] . ">";
            $this->EmailServers($to, $from, $sub, $body);
        }
        return true;
    }


    // get message save in message  table and message index table
    public function send_message($type, $message_type, $user_id, $reservation_id, $parameters = array())
    {
        $m_data = ClassRegistry::init('MessageText');
        $arr = array();
        $message_format = $m_data->find('first', array('conditions' => array('MessageText.type' => $type)));
        // pr($message_format);die;
        if (!empty($message_format)) {
            $sub = $message_format['MessageText']['subject'];
            if (isset($parameters) && !empty($parameters)) {
                foreach ($parameters as $param_name => $param_value) {
                    $sub = str_replace('[' . $param_name . ']', $param_value, $sub);
                }
            }
            $message = $message_format['MessageText']['message'];
            if (isset($parameters) && !empty($parameters)) {
                foreach ($parameters as $param_name => $param_value) {
                    $message = str_replace('[' . $param_name . ']', $param_value, $message);
                }
            }
            $message_id = $this->insert_new_message($sub, $message, $user_id, $reservation_id, $message_type);
            return $message_id;
        }

    }

    function insert_new_message($subject, $message, $user_id, $reservation_id, $message_type)
    {
        $m_data = ClassRegistry::init('Message');

        $data = array();
        $data['Message']['subject'] = $subject;
        $data['Message']['message'] = $message;
        $data['Message']['user_id'] = $user_id;
        $data['Message']['status'] = 1;
        $data['Message']['message_type'] = $message_type;
        $data['Message']['reservation_id'] = $reservation_id;
        $m_data->create(false);
        $m_data->save($data);

        return $m_data->id;
    }


    public function EmailServers($to = null, $from = null, $sub = null, $body = null)
    {
        $msg = 0;
        $today_year = date("Y");

        $body = str_replace('[CYEAR]', $today_year, $body);

        if (!empty($to) && !empty($from)) {
            $emails = ClassRegistry::init('EmailServer');
            $emails->create();
            $emails->set('email_to', $to);
            $emails->set('email_from', $from);
            $emails->set('subject', $sub);
            $emails->set('message', $body);
            $emails->save(null, false);
            $msg = 1;
        }
        return $msg;
    }

    public function SaveGetBrowser($tbl = null, $id = null)
    {
        $rt = 0;
        $ip = $_SERVER['REMOTE_ADDR'];
        $Date = DATE;
        if (!empty($tbl) && !empty($id)) {
            $model = ClassRegistry::init($tbl);
            $ua = $this->getBrowser();
            $yourbrowser = "browser: " . $ua['name'] . " " . $ua['version'] . " on " . $ua['platitudeform'] . " reports: <br >" . $ua['userAgent'];
            if ($model->updateAll(array($tbl . '.user_browser' => "'$yourbrowser'", $tbl . '.last_login_ip' => "'$ip'", $tbl . '.last_login' => "'$Date'"), array($tbl . '.id' => $id))) {
                $rt = 1;
            }
        }
        return $rt;
    }

    function getBrowser()
    {
        $u_agent = $_SERVER['HTTP_USER_AGENT'];
        $bname = 'Unknown';
        $platitudeform = 'Unknown';
        $ub = $version = "";

        //First get the platitudeform?
        if (preg_match('/linux/i', $u_agent)) {
            $platitudeform = 'linux';
        } elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
            $platitudeform = 'mac';
        } elseif (preg_match('/windows|win32/i', $u_agent)) {
            $platitudeform = 'windows';
        }

        // Next get the name of the useragent yes seperately and for good reason
        if (preg_match('/MSIE/i', $u_agent) && !preg_match('/Opera/i', $u_agent)) {
            $bname = 'Internet Explorer';
            $ub = "MSIE";
        } elseif (preg_match('/Firefox/i', $u_agent)) {
            $bname = 'Mozilla Firefox';
            $ub = "Firefox";
        } elseif (preg_match('/Chrome/i', $u_agent)) {
            $bname = 'Google Chrome';
            $ub = "Chrome";
        } elseif (preg_match('/Safari/i', $u_agent)) {
            $bname = 'Apple Safari';
            $ub = "Safari";
        } elseif (preg_match('/Opera/i', $u_agent)) {
            $bname = 'Opera';
            $ub = "Opera";
        } elseif (preg_match('/Netscape/i', $u_agent)) {
            $bname = 'Netscape';
            $ub = "Netscape";
        }

        // finally get the correct version number
        $known = array('Version', $ub, 'other');
        $pattern = '#(?<browser>' . join('|', $known) .
            ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
        if (!preg_match_all($pattern, $u_agent, $matches)) {
            // we have no matching number just continue
        }

        // see how many we have
        $i = count($matches['browser']);
        if ($i != 1) {
            //we will have two since we are not using 'other' argument yet
            //see if version is before or after the name
            if (strripos($u_agent, "Version") < strripos($u_agent, $ub)) {
                $version = $matches['version'][0];
            } else {
                $version = $matches['version'][1];
            }
        } else {
            $version = $matches['version'][0];
        }

        // check if we have a number
        if ($version == null || $version == "") {
            $version = "?";
        }

        return array(
            'userAgent' => $u_agent,
            'name' => $bname,
            'version' => $version,
            'platitudeform' => $platitudeform,
            'pattern' => $pattern
        );
    }

    function chk_user_email($email = null)
    {
        $user = ClassRegistry::init('User');
        $data = $user->find('count', array('recursive' => -1, 'conditions' => array('User.email' => $email)));
        return $data;
    }

    public function WorldData($id = null)
    {
        $list = null;
        $world = ClassRegistry::init('World');
        if (!empty($id) && is_numeric($id)) {
            $list = $world->find('list', array('recursive' => -1, 'conditions' => array('World.in_location' => $id), 'fields' => array('id', 'local_name')));
            $list = array('' => 'Select state') + $list;
        } else {
            $list = $world->find('list', array('recursive' => -1, 'conditions' => array('World.type' => 'CO', 'World.local_name IS NOT NULL'), 'fields' => array('id', 'local_name')));
            $list = array('' => 'Select Country') + $list;
        }
        return $list;
    }

    public function WorldData_name($id = null)
    {
        $list = null;
        $world = ClassRegistry::init('World');
        if (!empty($id) && is_numeric($id)) {
            $list = $world->find('list', array('recursive' => -1, 'conditions' => array('World.in_location' => $id), 'fields' => array('iso', 'local_name')));
            $list = array('' => 'Select state') + $list;
        } else {
            $list = $world->find('list', array('recursive' => -1, 'conditions' => array('World.type' => 'CO', 'World.local_name IS NOT NULL'), 'fields' => array('iso', 'local_name')));
            $list = array('' => 'Select Country') + $list;
        }
        return $list;
    }

    // get lat log based on address
    function Get_Lat_lng($address = null)
    {
        $place_name = null;
        if (!empty($address)) {
            //$address="bundi";
            $prepAddr = str_replace(' ', '+', $address);
            $geocode = file_get_contents('http://maps.google.com/maps/api/geocode/json?address=' . $prepAddr . '&sensor=false');
            $output = json_decode($geocode, true);
            if ($output['status'] == 'OK') {
                $lat = $output['results'][0]['geometry']['location']['lat'];
                $lng = $output['results'][0]['geometry']['location']['lng'];
                if (isset($output['results'][0]['formatted_address'])) {
                    $place_name = $output['results'][0]['formatted_address'];
                }
                $map_code = array('status' => 'ok', 'lat' => $lat, 'lng' => $lng, "PlaceName" => $place_name);
                return $map_code;
            } else {
                $map_code = array('status' => 'zero', 'lat' => '00.00', 'lng' => '00.00', "PlaceName" => $place_name);
                return $map_code;
            }
        } else {
            $map_code = array('status' => 'zero', 'lat' => '00.00', 'lng' => '00.00', "PlaceName" => $place_name);
            return $map_code;
        }
    }

    // mearge all array and get unique result based on retailer id
    function in_array_multi_dimension($merge_all_data)
    {
        $retailer_id = array();
        $result_data = array();
        foreach ($merge_all_data as $temp) {
            if (!in_array($temp['Retailer']['id'], $retailer_id)) {
                $result_data[] = $temp;
                $retailer_id[] = $temp['Retailer']['id'];
            } else {
                unset($temp);
            }
        }
        return $result_data;
    }

    function move_photo($image_array, $folder_name)
    {
        if ($image_array['error'] == 0 && !empty($image_array['name'])) {
            $id = $folder_name . "_" . rand(1000, 999999);
            $file_ext = @end(explode(".", $image_array['name']));
            $product_image_name = $id . "." . $file_ext;
            $product_image_path = WWW_ROOT . 'data/' . $folder_name . '/' . $product_image_name;
            if (move_uploaded_file($image_array['tmp_name'], $product_image_path)) {
                return $product_image_name;
            }
        }
    }

    function get_provider_id($reservation_lat, $reservation_long)
    {

        $user = ClassRegistry::init('User');
        $loadAllProviders = $user->find('all', array('conditions' => array('User.lat' => '', 'User.lat' => '')));;
    }


    public function GetProviderByGeoLocationServiceType($reservation, $service_type_id, $radius = null, $count_call = null, $vehicle_id = null)
    {
        $NearByRetailer = array();
        if (!empty($radius)) {

            $NearByRetailer = $this->find_latlng_in_radius_serivce($reservation, $service_type_id, $radius, false, $vehicle_id);

            if (empty($NearByRetailer) && $count_call <= 3) {
                $radius = $radius * 2;
                if ($count_call == null) {
                    $count_call = 1;
                }
                $count_call++;
                return $this->GetProviderByGeoLocationServiceType($reservation, $service_type_id, $radius, $count_call, $vehicle_id);
            } else {
                return $NearByRetailer;
            }
        } else {
            return $NearByRetailer;
        }
    }

    function find_latlng_in_radius_serivce($reservation = null, $service_type_id = null, $radius = null, $miles = false, $vehicle_id = null)
    {

        $latitude = $reservation['Reservation']['pickup_loc_lat'];
        $longitude = $reservation['Reservation']['pickup_loc_long'];
        $service_id = $reservation['Reservation']['service_id'];

        $radius = $miles ? $radius : ($radius * 0.621371192);
        $lng_min = $longitude - $radius / abs(cos(deg2rad($latitude)) * 69);
        $lng_max = $longitude + $radius / abs(cos(deg2rad($latitude)) * 69);
        $lat_min = $latitude - ($radius / 69);
        $lat_max = $latitude + ($radius / 69);
        // get all address and then group based on retailer id
        $ServiceInformation = ClassRegistry::init('ServiceInformation');
        $ServiceInformation->bindModel(array('belongsTo' => array('Provider', 'User')));
        $condition = "";
        if (isset($vehicle_id) && !empty($vehicle_id)) {
            $condition = array('ServiceInformation.vehicle_id' => $reservation['Reservation']['vehicle_id']);
        }
        $loadAllProviders = $ServiceInformation->find('all', array('recursive' => 2, 'conditions' => array(
            'User.role' => '3', 'User.status' => '1', 'User.country_name' => strtolower($reservation['Reservation']['currently_in']),
            'User.lat BETWEEN ' . $lat_min . ' AND ' . $lat_max,
            'User.log BETWEEN ' . $lng_min . ' AND ' . $lng_max,
            'ServiceInformation.service_type_id' => $service_type_id,
            $condition,
            'ServiceInformation.service_id' => $service_id),
            'order' => array('ServiceInformation.rate'), 'limit' => 3));

        return $loadAllProviders;
    }


    // insert one entry into message table
    function insert_message($reservation, $subject, $message)
    {
        $Message = ClassRegistry::init('Message');
        $MessageIndex = ClassRegistry::init('MessageIndex');
        $user_id = ME;
        $reservation_id = $reservation['Reservation']['id'];

        return $message_id = $Message->Add($subject, $message, $user_id, $reservation_id, '2');
    }


// function for suggest provider
    public function suggest_provider()
    {
        $user = ClassRegistry::init('User');
        $condition = array();
        $condition['User.role'] = AGENT_ROLE;
        $user->bindModel(array('hasOne' => array('Provider')));
        $suggested_all_provider = $user->find('all', array('conditions' => $condition, 'limit' => 3, 'order' => "(Provider.id) DESC"));
        //pr($suggested_all_provider);die;
        return $suggested_all_provider;
    }

    function update_transaction_log($reservation_id, $provider_id, $status)
    {

        $TransactionLog = ClassRegistry::init('TransactionLog');
        $TransactionLog->updateAll(array('status' => $status), array('provider_id' => $provider_id, 'reservation_id' => $reservation_id));


    }

    // get lat log based on system ip
    public function getLocationInfoByIp($ipget)
    {
        $client = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote = $ipget;
        $result = array();
        if (filter_var($client, FILTER_VALIDATE_IP)) {
            $ip = $client;
        } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
            $ip = $forward;
        } else {
            $ip = $remote;
        }
        if ($ip == MY_IP) {
            $ip = "122.176.83.11";
        }
        $ip_data = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));

        if (!empty($ip_data)) {
            $result[] = $ip_data->geoplugin_countryCode;
            $result[] = $ip_data->geoplugin_countryName;
        }
        return $result;
    }

    // calculate hours
    function calculate_hours($start_date, $end_date)
    {
        $date1timestamp = strtotime($start_date);
        $date2timestamp = strtotime($end_date);
        $all = (($date2timestamp - $date1timestamp) / 3600);
        return $all;
    }

    // caluclate distance between two point
    public function calculated_distance_two_point($point_a, $point_b, $mode = "driving")
    {
//        walking,bicycling
        $route_data = @json_decode(file_get_contents("http://maps.googleapis.com/maps/api/directions/json?origin=" . urlencode($point_a) . "&destination=" . urlencode($point_b) . "&alternatives=true&sensor=false&mode=" . $mode));
        if (!empty($route_data->routes[0])) {
            $result['distance'] = $route_data->routes[0]->legs[0]->distance->text;
            $result['status'] = 1;
        } else {
            $result['status'] = 0;
        }
        return $result;
    }

    function get_client_ip()
    {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if (getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if (getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if (getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if (getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if (getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';

        return $ipaddress;
    }


    // get city,state and country name based on address based
    function get_state_city_country($address1, $address2)
    {
        // make an array for address One country state ,city
        $route_data = @json_decode(file_get_contents("http://maps.googleapis.com/maps/api/geocode/json?address=" . urlencode($address1)));
        $route_array = $route_data->results[0]->address_components;

        if (isset($route_array) && !empty($route_array)) {
            $address1_result_array = array();
            foreach ($route_array as $key => $value) {
                if ($value->types[0] == "administrative_area_level_2") {
                    $address1_result_array['city'] = $value->long_name;
                } else if ($value->types[0] == "administrative_area_level_1") {
                    $address1_result_array['state'] = $value->long_name;
                } else if ($value->types[0] == "country") {
                    $address1_result_array['country'] = $value->long_name;
                }
            }

        }
        // make an array for address two country state ,city
        $address2_route_data = @json_decode(file_get_contents("http://maps.googleapis.com/maps/api/geocode/json?address=" . urlencode($address2)));

        $address2_route_array = $address2_route_data->results[0]->address_components;

        if (isset($address2_route_array) && !empty($address2_route_array)) {
            $address2_result_array = array();
            foreach ($address2_route_array as $key => $value) {
                if ($value->types[0] == "administrative_area_level_2") {
                    $address2_result_array['city'] = $value->long_name;
                } else if ($value->types[0] == "administrative_area_level_1") {
                    $address2_result_array['state'] = $value->long_name;
                } else if ($value->types[0] == "country") {
                    $address2_result_array['country'] = $value->long_name;
                }
            }

        }
        if (!empty($address1_result_array) && !empty($address2_result_array)) {

            // check country name
            $result = "";
            if ($address1_result_array['country'] != $address2_result_array['country']) {
                $result = "country";
            } else if ($address1_result_array['state'] != $address2_result_array['state']) {
                $result = "state";
            } else {
                $result = "city";
            }
            return $result;

        }

    }

    public function getUnreadMessage($user_id)
    {
        $messageIndex = ClassRegistry::init('MessageIndex');
        $unread_message = $messageIndex->find('count', array('conditions' => array('MessageIndex.recipient_id' => $user_id, 'MessageIndex.is_read' => 0, 'MessageIndex.deleted' => 0)));
        return $unread_message;

    }

// get service id based on reservation
    public function get_service_id($reservation)
    {
        if ($reservation['Reservation']['service_id'] == 1) {
            if ($reservation['Reservation']['estimated_hours'] >= 24) {
                $service_id = 3;
            } else {
                if (strtolower($reservation['Reservation']['pick_up_location_city']) == strtolower($reservation['Reservation']['drop_off_location_city'])) {
                    $service_id = 1;
                } else {
                    $service_id = 2;
                }

            }
        } else if ($reservation['Reservation']['service_id'] == 2) {
            if (strtolower($reservation['Reservation']['pick_up_location_city']) == strtolower($reservation['Reservation']['drop_off_location_city'])) {
                $service_id = 1;
            } else {
                $service_id = 2;
            }

        } else if ($reservation['Reservation']['service_id'] == 3) {
            if ($reservation['Reservation']['estimated_hours'] >= 24) {
                $service_id = 3;
            } else {
                $service_id = 2;
            }
        }
        return $service_id;

    }

    // for driver all three option ....km.daily,hours
    public function suggested_provider($reservation)
    {
        // if service start date and end date diff days then
        // then calculate DAILY prices
        if ($reservation['Reservation']['estimated_hours'] >= 24) {
//            if type == daily then service type id ==3
            $all_provider = $this->find_best_price(3, $reservation);
        } else {
            // if pickup and drop off location Diff in city then
            // based on KILOMETERS

            // if pickup and drop off location out of city then
            // based on HOURLY

            // calculate diff in city or out of city

            // city means "in city" else out of city
            // check diff between city
            if (strtolower($reservation['Reservation']['pick_up_location_city']) == strtolower($reservation['Reservation']['drop_off_location_city'])) {
                //  if type == KILOMETERS then service type id ==1
                $all_provider = $this->find_best_price(1, $reservation);
            } else {
                //   if type == HOURLY then service type id ==2
                $all_provider = $this->find_best_price(2, $reservation);
            }

        }
        if (isset($all_provider) && !empty($all_provider)) {
            return $all_provider;
        }

    }

    // for taxi only two option  ....Only the per Hour and Per Kilometer should be the 2 options
    public function suggested_provider_for_taxi($reservation)
    {

        // if pickup and drop off location Diff in city then
        // based on KILOMETERS

        // if pickup and drop off location out of city then
        // based on HOURLY

        // calculate diff in city or out of city

        // city means "in city" else out of city
        // check diff between city
        if (strtolower($reservation['Reservation']['pick_up_location_city']) == strtolower($reservation['Reservation']['drop_off_location_city'])) {
            //  if type == KILOMETERS then service type id ==1
            $all_provider = $this->find_best_price(1, $reservation);
        } else {
            //   if type == HOURLY then service type id ==2
            $all_provider = $this->find_best_price(2, $reservation);
        }


        if (isset($all_provider) && !empty($all_provider)) {
            return $all_provider;
        }

    }

    // for vehicle only two option  ....Only the per Hour and Per Day should be the 2 options.
    public function suggested_provider_for_vehicle($reservation)
    {
        if ($reservation['Reservation']['estimated_hours'] >= 24) {
            $all_provider = $this->find_best_price_for_vehicle(3, $reservation);
        } else {
            $all_provider = $this->find_best_price_for_vehicle(2, $reservation);
        }
        if (isset($all_provider) && !empty($all_provider)) {
            return $all_provider;
        }

    }


    public function find_best_price($service_type_id, $reservation)
    {

        return $all_data = $this->GetProviderByGeoLocationServiceType($reservation, $service_type_id, BOARD_CAST_MILE);

    }

    public function find_best_price_for_vehicle($service_type_id, $reservation)
    {

        return $all_data = $this->GetProviderByGeoLocationServiceType($reservation, $service_type_id, BOARD_CAST_MILE, null, $reservation['Reservation']['vehicle_id']);
    }


    public function calculated_price_type_put_price($reservation, $your_price)
    {
        $type = "";
        if ($reservation['Reservation']['service_id'] == 1) {
            if ($reservation['Reservation']['estimated_hours'] >= 24) {
//            if type == daily then service type id ==3
                $service_type_id = 3;
                $type = "Per Day";
            } else {
                // if pickup and drop off location Diff in city then
                // based on KILOMETERS

                // if pickup and drop off location out of city then
                // based on HOURLY

                if (strtolower($reservation['Reservation']['pick_up_location_city']) == strtolower($reservation['Reservation']['drop_off_location_city'])) {
                    //  if type == KILOMETERS then service type id ==1
                    $service_type_id = 1;
                    $type = "Per KM";
                } else {
                    //   if type == HOURLY then service type id ==2
                    $service_type_id = 2;
                    $type = "Per Hour";
                }
            }

        } else if ($reservation['Reservation']['service_id'] == 2) {

            if (strtolower($reservation['Reservation']['pick_up_location_city']) == strtolower($reservation['Reservation']['drop_off_location_city'])) {
                $service_type_id = 1;
                $type = "Per KM";
            } else {
                //   if type == HOURLY then service type id ==2
                $service_type_id = 2;
                $type = "Per Hour";
            }
        } else if ($reservation['Reservation']['service_id'] == 3) {
            if ($reservation['Reservation']['estimated_hours'] >= 24) {
//            if type == daily then service type id ==3
                $service_type_id = 3;
                $type = "Per Day";
            } else {
                //   if type == HOURLY then service type id ==2
                $service_type_id = 2;
                $type = "Per Hour";
            }

        }
        $total_cost = "";
        if ($service_type_id == 1) {
            $total_cost = ($your_price * $reservation['Reservation']['map_estimated_distance']);
        } elseif ($service_type_id == 2) {
            $min_rate = $your_price / 60;
            $total_cost = ($your_price * $reservation['Reservation']['map_estimated_hours']) + ($min_rate * $reservation['Reservation']['map_estimated_min']);
        } else if ($service_type_id == 3) {
            // hours convert into days
            $time_array = $this->hours_to_days($reservation['Reservation']['estimated_hours']);
            $total_day_cost = $your_price * $time_array[0];
            $hours_price = ($your_price / 60);
            $total_hours_cost = $hours_price * $time_array[1];
            $total_cost = $total_day_cost + $total_hours_cost;
        }

        return $result = array(number_format($total_cost, 2, '.', ''), $type);
    }

    public function calculated_price($reservation, $provider_id)
    {
        if ($reservation['Reservation']['service_id'] == 1) {
            if ($reservation['Reservation']['estimated_hours'] >= 24) {
//            if type == daily then service type id ==3
                $service_type_id = 3;
            } else {
                // if pickup and drop off location Diff in city then
                // based on KILOMETERS

                // if pickup and drop off location out of city then
                // based on HOURLY

                if (strtolower($reservation['Reservation']['pick_up_location_city']) == strtolower($reservation['Reservation']['drop_off_location_city'])) {
                    //  if type == KILOMETERS then service type id ==1
                    $service_type_id = 1;
                } else {
                    //   if type == HOURLY then service type id ==2
                    $service_type_id = 2;
                }
            }
        } else if ($reservation['Reservation']['service_id'] == 2) {
            if (strtolower($reservation['Reservation']['pick_up_location_city']) == strtolower($reservation['Reservation']['drop_off_location_city'])) {
                //  if type == KILOMETERS then service type id ==1
                $service_type_id = 1;
            } else {
                //   if type == HOURLY then service type id ==2
                $service_type_id = 2;
            }
        } else if ($reservation['Reservation']['service_id'] == 3) {
            if ($reservation['Reservation']['estimated_hours'] >= 24) {

                $service_type_id = 3;
            } else {
                //   if type == HOURLY then service type id ==2
                $service_type_id = 2;
            }
        }
        $Provider = ClassRegistry::init('Provider');
        $Provider->bindModel(array('belongsTo' => array('User')));
        $Provider->bindModel(array('hasOne' => array('ServiceInformation' => array('conditions' => array('ServiceInformation.service_type_id' => $service_type_id)))));
        $provider_data = $Provider->find('first', array('recursive' => 2, 'conditions' => array('Provider.id' => $provider_id)));
// calculate total cost
        $total_cost = "";
        if ($service_type_id == 1) {
            $total_cost = ($provider_data['ServiceInformation']['rate'] * $reservation['Reservation']['map_estimated_distance']);
        } elseif ($service_type_id == 2) {
            $min_rate = $provider_data['ServiceInformation']['rate'] / 60;
            $total_cost = ($provider_data['ServiceInformation']['rate'] * $reservation['Reservation']['map_estimated_hours']) + ($min_rate * $reservation['Reservation']['map_estimated_min']);
        } else if ($service_type_id == 3) {
            // hours convert into days
            $time_array = $this->hours_to_days($reservation['Reservation']['estimated_hours']);
            $total_day_cost = $provider_data['ServiceInformation']['rate'] * $time_array[0];
            $hours_price = ($provider_data['ServiceInformation']['rate'] / 60);
            $total_hours_cost = $hours_price * $time_array[1];
            $total_cost = $total_day_cost + $total_hours_cost;
        }
        return number_format($total_cost, 2, '.', '');
    }

    function hours_to_days($hours)
    {
        $remaining_hours = $hours % 24;
        $total_days = floor($hours / 24);
        $time_array = array($total_days, $remaining_hours);
        return $time_array;
    }

    // get lat log based on address
    function Get_Lat_lng_city($address = null)
    {
        $city_name = $place_name = null;
        if (!empty($address)) {
            $prepAddr = str_replace(' ', '+', $address);
            $geocode = file_get_contents('http://maps.google.com/maps/api/geocode/json?address=' . $prepAddr . '&sensor=false');
            $output = json_decode($geocode, true);
            if ($output['status'] == 'OK') {
                $lat = $output['results'][0]['geometry']['location']['lat'];
                $lng = $output['results'][0]['geometry']['location']['lng'];
                $city_name = "";
                foreach ($output['results'][0]['address_components'] as $key => $value) {
                    if ($value['types'][0] == "administrative_area_level_2") {
                        $city_name = $value['long_name'];
                    }
                }
                if (isset($output['results'][0]['formatted_address'])) {
                    $place_name = $output['results'][0]['formatted_address'];
                }
                $map_code = array('status' => 'ok', 'lat' => $lat, 'lng' => $lng, "PlaceName" => $place_name, 'city_name' => $city_name);
                return $map_code;
            } else {
                $map_code = array('status' => 'zero', 'lat' => '00.00', 'lng' => '00.00', "PlaceName" => $place_name);
                return $map_code;
            }
        } else {
            $map_code = array('status' => 'zero', 'lat' => '00.00', 'lng' => '00.00', "PlaceName" => $place_name);
            return $map_code;
        }
    }


    function get_distance_two_point($start, $finish)
    {
        $theta = $start[1] - $finish[1];
        $distance = (sin(deg2rad($start[0])) * sin(deg2rad($finish[0]))) + (cos(deg2rad($start[0])) * cos(deg2rad($finish[0])) * cos(deg2rad($theta)));
        $distance = acos($distance);
        $distance = rad2deg($distance);
        $distance = $distance * 60 * 1.1515;
        return round($distance, 2);

    }

    function get_driving_information($start, $finish)
    {
        $min = $hours = $distance = 0;
        if (strcmp($start, $finish) == 0) {
            return array('distance' => $distance, 'hours' => $hours, 'min' => $min);
        }
        $start = urlencode($start);
        $finish = urlencode($finish);

        $url = 'http://maps.googleapis.com/maps/api/directions/xml?origin=' . $start . '&destination=' . $finish . '&sensor=false';
        if ($data = file_get_contents($url)) {
            $xml = new SimpleXMLElement($data);
            if (isset($xml->route->leg->duration->value) AND (int)$xml->route->leg->duration->value > 0) {
                $distance = (int)$xml->route->leg->distance->value / 1000 / 1.609344;
                $hours = gmdate("H", (int)$xml->route->leg->duration->value);
                $min = gmdate("i", (int)$xml->route->leg->duration->value);
            } else {
                throw new Exception('Could not find that route');
            }

            return array('distance' => $distance, 'hours' => $hours, 'min' => $min);
        } else {
            throw new Exception('Could not resolve URL');
        }
    }

    // function for send email
    function send_email($reservation, $type, $parameter_array = array())
    {

    }


    // function for check validation for service infor
    function check_service_validation($data)
    {
        $result['status'] = 0;
        if (isset($data) && !empty($data)) {
            foreach ($data as $value) {
                // check value should be numeric and not blank
                if (is_numeric($value['ServiceInformation']['rate']) && !empty($value['ServiceInformation']['rate'])) {

                } else {
                    return $result;
                }
            }
            $result['status'] = 1;
        } else {

        }

        return $result;
    }

    // save user table if user not set detail
    function save_user_contact_info($reservation, $reservation_data)
    {

        // check user saved or not
        if (empty($reservation_data['User']['country_name']) || $reservation_data['User']['country_id']) {
            $user = ClassRegistry::init('User');
            $address = $reservation['Reservation']['country_name'] . " " . $reservation['Reservation']['state_name'] . " " . $reservation['Reservation']['city'];
            $pick_up_lat_log_city = $this->Get_Lat_lng_city($address);
            $user_info['User']['lat'] = $pick_up_lat_log_city['lat'];
            $user_info['User']['log'] = $pick_up_lat_log_city['lng'];
            $user_info['User']['id'] = $reservation_data['Reservation']['user_id'];
            $user_info['User']['country_name'] = $reservation['Reservation']['country_name'];
            $user_info['User']['country_id'] = $reservation['Reservation']['country_id'];
            $user_info['User']['state_name'] = $reservation['Reservation']['state_name'];
            $user_info['User']['state_id'] = $reservation['Reservation']['state_id'];
            $user_info['User']['city'] = $reservation['Reservation']['city'];
            $user_info['User']['address'] = $reservation['Reservation']['address'];
            $user->save($user_info);
        }

    }

    function SendSMS($people = null)
    {
        App::import('Vendor', 'sms/Services/Twilio');
        $AccountSid = TWILIO_ACCOUNT_SID;
        $AuthToken = TWILIO_AUTH_TOKEN;
        $client = new Services_Twilio($AccountSid, $AuthToken);
        $result = array();
        $result['status'] =0;
        if (isset($people) && is_array($people)) {
            try {
                foreach ($people as $number => $message) {
                    $client->account->sms_messages->create(TWILIO_ADMIN_NO, $number, "$message");
                    $result['status'] =1;
                    $result['message'] = "Message successfully send";
                }
            } catch (Exception $e) {
                $result['message']  = $e->getMessage();
            }
        }else{
            $result['message']  = "Please insert phone number";
        }
        return $result;
    }

    public function create_message_text($type = null, $arr = null) { //$arr=array('[aa]'=>'xyz');
        $sms = ClassRegistry::init('SmsText');
        $smsformat = $sms->find('first', array('conditions' => array('SmsText.type' => $type)));
        if (!empty($smsformat)) {
            $str = $smsformat['SmsText']['text'];
            if (!empty($arr)) {
                foreach ($arr as $name => $value) {
                    $str = str_replace('[' . $name . ']', $value, $str);
                }
            }
        } else {
            $str = "Visit you guestnest account, you have something new ";
        }
        return $str;
    }


    // when send sms then insert into sms server table
    function InsertSmsServer($sms_server_array){
        $smsServer = ClassRegistry::init('SmsServer');
        $smsServer->save($sms_server_array);
    }

}

?>

