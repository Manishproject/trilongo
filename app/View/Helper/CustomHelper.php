<?php
App::uses('Helper', 'View');

class CustomHelper extends Helper
{

    public $helpers = array('Form', 'Session');

    public function getLocationFromIP($IP = null)
    {
        if (!is_null($IP)) {
            $ch = curl_init();
            @curl_setopt($ch, CURLOPT_VERBOSE, 1);
            @curl_setopt($ch, CURLOPT_HEADER, 0);
            @curl_setopt($ch, CURLOPT_POST, 0);
            @curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            @curl_setopt($ch, CURLOPT_URL, 'http://api.hostip.info/get_html.php?position=true&ip=' . $IP);
            @curl_setopt($ch, CURLOPT_TIMEOUT, 120);
            $location_info = @curl_exec($ch);

            $loc['country'] = $this->get_string_between($location_info, "Country: ", "\nCity: ");
            $loc['location'] = $this->get_string_between($location_info, "City: ", "\nLatitude: ");
            $loc['latitude'] = $this->get_string_between($location_info, "Latitude: ", "\nLongitude: ");
            $loc['longitude'] = $this->get_string_between($location_info, "Longitude: ", "\nIP: ");

            return $loc;
        } else {
            return false;
        }
    }

    public function get_user_full_name($id = null)
    {
        //Getting user's details
        $userid = $this->Session->read("Auth.User.id");
        if (!is_null($id))
            $userid = $id;
        $user_details = ClassRegistry::init("UserDetail")->find("first", array("conditions" => array("UserDetail.user_id" => $userid)));
        if (!empty($user_details) && is_array($user_details)) {
            $user_first_name = $user_details["UserDetail"]["first_name"];
            $user_last_name = $user_details["UserDetail"]["last_name"];
        } else {
            $user_first_name = "UNKNOWN";
            $user_last_name = "USER";
        }
        return $user_first_name . " " . $user_last_name;
    }

    function get_user_data($email = null, $password = null, $id = null)
    {
        $user = ClassRegistry::init("User");
        if (!empty($id)) {
            $conditions = array("User.id" => $id);
        } else {
            if (empty($password)) {
                $conditions = array("User.email" => $email);
            } elseif (empty($email)) {
                $conditions = array("User.password" => $password);
            } else {
                $conditions = array("User.email" => $email, "User.password" => $password);
            }
        }
        $user_data = $user->find(
            "first", array(
                "conditions" => $conditions,
                "fields" => array("User.id", "User.mas_role_id", "User.email", "User.username", "User.status", "User.last_login", "UserDetail.first_name", "UserDetail.last_name", "UserDetail.gender", "User.verified", "User.activation_token", "UserDetail.user_image")
            )
        );

        if (!empty($user_data)) {
            //Collecting all user details
            $user_details = $user_data["UserDetail"];
            $user_data = $user_data["User"];
            return array_merge($user_data, $user_details);
        } else {
            return false;
        }
    }

    public function get_user_details($id = null)
    {
        //Getting user's details
        $user_details = ClassRegistry::init("User")->find("first", array("conditions" => array("User.id" => $id)));
        return $user_details;
    }

    public function get_week_days($selected = null)
    {
        $selected_days = explode(",", $selected);
        $week_arr = array(
            1 => "Mon",
            2 => "Tue",
            3 => "Wed",
            4 => "Thu",
            5 => "Fri",
            6 => "Sat",
            7 => "Sun",
        );

        $full_week = "<ul class='week week_list clearfix'>";
        for ($week_day = 1; $week_day <= sizeof($week_arr); $week_day++) {
            if (in_array($week_day, $selected_days)) {
                $full_week .= "<li><a href='javascript:void(0)' id='" . $week_day . "' style='background-color:#E40D14'>" . $week_arr[$week_day] . "</a></li>";
            } else {
                $full_week .= "<li><a href='javascript:void(0)' id='" . $week_day . "'>" . $week_arr[$week_day] . "</a></li>";
            }
        }
        $full_week .= "</ul>";
        return $full_week;
    }

    public function make_combo($start = null, $end = null, $multiple = null)
    {
        $combo_array = array();
        $multiple_range = null;
        for ($range = $start; $range <= $end; $range++) {
            if (is_null($multiple)) {
                $combo_array[$range] = $range;
            } else {
                if (is_null($multiple)) {
                    $combo_array[$range] = $range;
                } else {
                    if ($range == 1) {
                        $combo_array[$range] = $range;
                        $multiple_range = $range * $multiple;
                        $combo_array[$multiple_range] = $multiple_range;
                    } else {
                        $multiple_range = $range * $multiple;
                        $combo_array[$multiple_range] = $multiple_range;
                    }
                }
            }
        }
        return $combo_array;
    }


    public function get_settings($slug = null, $fields = null)
    {
        $settings_inst = ClassRegistry::init("SiteSetting");
        if (empty($slug))
            $settings = $settings_inst->find("all");
        else
            $settings = $settings_inst->find("first", array("conditions" => array("slug" => array($slug))));

        if (!empty($settings)) {
            if (empty($slug)) {
                if (empty($fields)) {
                    return $settings;
                } else {
                    foreach ($settings as $st) {
                        $slug = $st["SiteSetting"]["slug"];
                        if (in_array($slug, $fields)) {
                            $settings_grp[] = $st["SiteSetting"]["value"];
                        }
                    }
                    return $settings_grp;
                    die;
                }
            } else {
                return $settings["SiteSetting"]["value"];
            }
        } else {
            return false;
        }
    }


    public function get_ip_data($return_data = null)
    {
        //$ip_data = json_decode(file_get_contents("http://ipinfo.io/json"),true);
        //$ip_data = json_decode(file_get_contents("http://ipinfo.io/".$_SERVER["REMOTE_ADDR"]),true);
        //$ip_data = @json_decode(file_get_contents("http://ipinfo.io/122.161.28.104"),true);

        $ch = curl_init(IP_INFO_URL);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $ip_data = @curl_exec($ch);
        $ip_data = json_decode($ip_data, true);
        if (isset($ip_data["hostname"]) && $ip_data["hostname"] == "No Hostname" || isset($ip_data["lists"][0]) && $ip_data["lists"][0] == "bogon") {
            return false;
        } else {
            if (!is_null($return_data))
                return $ip_data[$return_data];
            else
                return $ip_data;
        }
    }

    public function get_ip_latlong($latlong)
    {
        $latlong = explode(",", $latlong);
        return array("lat" => $latlong[0], "lng" => $latlong[1]);
    }

    public function get_timezone($mode = null)
    {
        $lat_long = $this->get_ip_data("loc");
        if ($lat_long == false) {
            return $lat_long;
        } else {
            $lat_lng_arr = $this->get_ip_latlong($lat_long);
            $timezone_data = json_decode(file_get_contents("https://maps.googleapis.com/maps/api/timezone/json?location=" . $lat_lng_arr["lat"] . "," . $lat_lng_arr["lng"] . "&timestamp=1331161200&sensor=false"), true);
            if (!is_array($timezone_data)) {
                return false;
            } else {
                if ($timezone_data["status"] == "OVER_QUERY_LIMIT" || $timezone_data["status"] == "ZERO_RESULTS") {
                    return 0;
                } else {
                    if ($mode == "timezone") {
                        return $timezone_data["timeZoneId"];
                    } elseif ($mode == "offset") {
                        if ($timezone_data["rawOffset"] == 0)
                            $timezone_data["rawOffset"] = 1;

                        return $timezone_data["rawOffset"];
                    } else {
                        return $timezone_data;
                    }
                }

            }
        }
    }

    public function get_global_date($date, $timezone_offset, $set_get = null)
    {
        $timestamp = strtotime($date);
        if (substr($timezone_offset, 0, 1) == "-") {
            $diff = abs($timezone_offset);
            if ($set_get == "set")
                $timestamp += $diff;
            else
                $timestamp -= $diff;
        } else {
            if ($set_get == "set")
                $timestamp -= $timezone_offset;
            else
                $timestamp += $timezone_offset;
        }
        return date("Y-m-d H:i:s", $timestamp);
    }

    function get_avatar_images()
    {
        $avatar_inst = ClassRegistry::init("Avatar");
        $avatars = $avatar_inst->find(
            "all",
            array(
                "conditions" => array(
                    "status" => 1
                )
            )
        );
        return $avatars;
    }

    function get_address_formate($user_data = array())
    {
        $address = "";
        if (isset($user_data['User']['country_name']) && !empty($user_data['User']['country_name'])) {
            $address .= ucfirst($user_data['User']['country_name']) . ", ";
        }
        if (isset($user_data['User']['state_name']) && !empty($user_data['User']['state_name'])) {
            $address .= ucfirst($user_data['User']['state_name']) . ", ";
        }
        if (isset($user_data['User']['city']) && !empty($user_data['User']['city'])) {
            $address .= ucfirst($user_data['User']['city']) . ", ";
        }
        if (isset($user_data['User']['zip']) && !empty($user_data['User']['zip'])) {
            $address .= ucfirst($user_data['User']['zip']);
        }
        return $address;

    }

    // get retailer_image if exist other wise show defaulyt image
    function check_image($image_name = null, $type = null)
    {
// here type == folder name
        if (!empty($image_name)) {
            $image = realpath('data/' . $type . '/') . "/" . $image_name;
            if (file_exists($image)) {
                return $image_name;
            } else {
                return $type . '_default.png';
            }
        } else {
            return $type . '_default.png';
        }
    }
}