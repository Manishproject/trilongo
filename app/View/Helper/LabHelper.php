<?php

class LabHelper extends AppHelper
{

    public $helpers = array('Html', 'Form', 'JqueryEngine', 'Session', 'Text', 'Time', 'Paginator');

    public function MenuNum()
    {
        $arr = array();
        $Retailer = ClassRegistry::init('Retailer');
        $User = ClassRegistry::init('User');
        $Mail = ClassRegistry::init('Mail');
        $product = ClassRegistry::init('Product');
        $new_retailers = $Retailer->find('count', array('conditions' => array('Retailer.status' => 0), 'recursive' => -1));
        $all_retailers = $Retailer->find('count', array('conditions' => array(), 'recursive' => -1));
        $a = array(1, 3);
        $AllUsers = $User->find('count', array('recursive' => -1, 'conditions' => array('User.role NOT' => $a, 'status' => 1)));
        $AllMails = $Mail->find('count', array('recursive' => -1));
        $AllProduct = $product->find('count', array('recursive' => -1));

        $arr = array('NewRetailer' => $new_retailers, 'AllRetailer' => $all_retailers, 'AllUsers' => $AllUsers, 'AllMails' => $AllMails, 'AllProduct' => $AllProduct);
        $arr = array_replace($arr, array_fill_keys(array_keys($arr, 0), null));

        return $arr;
    }

    public function ShowDate($str = null)
    {
        $date = null;
        if ($str != "0000-00-00 00:00:00") {
            if (DateTime::createFromFormat('Y-m-d G:i:s', $str) !== FALSE || DateTime::createFromFormat('Y-m-d', $str) !== FALSE) {
                $date = date('l F d y g:i a', strtotime($str));
            }
        } else {
            $date = "..";
        }
        return $date;
    }

    public function GetTableData($tbl = NULL, $id = null)
    {
        $user = ClassRegistry::init($tbl);
        $data = $user->find('first', array('conditions' => array($tbl . '.id' => $id), 'recursive' => -1));
        return $data;
    }

    public function BusinessType()
    {
        return array('medical' => 'Medical', 'recreational' => 'recreational');
    }

    public function TimeArr()
    {
        $arr = array();

        for ($i = 0; $i < 24; $i++) {
            $arr[sprintf("%02s", $i) . ":00:00"] = date("h:i A", strtotime($i . ":00:00"));
        }
        return $arr;
    }

    public function menu_list()
    {
        $RetailerMenu = ClassRegistry::init('RetailerMenu');
        $menu_con_or['RetailerMenu.global'] = true;
        $menu_con_or['RetailerMenu.user_id'] = ME;
        $menu_options = $RetailerMenu->find('list', array('conditions' => array(
                'RetailerMenu.status' => 1,
                'or' => $menu_con_or
            )
            )
        );
        return $menu_options;
    }

    public function menu_list_product($user_id = null)
    {
        $RetailerMenu = ClassRegistry::init('RetailerMenu');
        $Product = ClassRegistry::init('Product');
        $menu_con_or['RetailerMenu.global'] = true;
        $menu_con_or['RetailerMenu.user_id'] = $user_id;
        $RetailerMenu->bindModel(array(
                'hasMany' => array(
                    'Product' => array(
                        'conditions' => array(
                            'Product.user_id' => $user_id,
                            'Product.status' => 1,
                        ),
                    )
                )
            )
        );
        $menu_options = $RetailerMenu->find('all', array('conditions' => array(
                'RetailerMenu.status' => 1,
                'or' => $menu_con_or
            )
            )
        );
        return $menu_options;
    }

    public function product_listing()
    {
        $ProductList = ClassRegistry::init('Product');
        $ProductListData = $ProductList->find('all', array('conditions' => array(
                'Product.user_id' => ME
            )
            )
        );
        return $ProductListData;
    }

    public function get_retailer_availablity()
    {
        $retailer_availablity = ClassRegistry::init('AvailabilityRecord');
        $retailer_availablity_data = $retailer_availablity->find('all', array('conditions' => array(
                'AvailabilityRecord.status' => 1
            )
            )
        );
        return $retailer_availablity_data;
    }

    /* days name 
     * */

    public function advertise_size()
    {
        return array('320x320' => '320x320', '300x250' => '300x250', '728x90' => '728x90', '160x600' => '160x600', '1600x1210' => '1600x1210', '480x360' => '480x370', '882x135' => '882x135');
    }

    public function get_retailer_availablity_search_page()
    {
        $retailer_availablity = ClassRegistry::init('AvailabilityRecord');

        $extra_array = array('open_now' => 'open Now', 'deal' => 'deals');
        $retailer_availablity_data = $retailer_availablity->find('all', array('conditions' => array(
                'AvailabilityRecord.status' => 1
            ), 'limit' => '5'
            )
        );
        foreach ($extra_array as $key => $value) {
            $set_array = array('AvailabilityRecord' => array('name' => $value, 'alias' => $key));
            $retailer_availablity_data[] = $set_array;
        }

        return $retailer_availablity_data;
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

// get retailer deal based on retailer id  
    function get_retailer_deal($reatiler_id = null)
    {
        $retailer_deal_data = array();
        if (!empty($reatiler_id)) {
            $product_deal = ClassRegistry::init('ProductDeal');

            $retailer_deal_data = $product_deal->find('all', array('conditions' => array(
                    'ProductDeal.status' => $reatiler_id,
                    'ProductDeal.retailer_id' => $reatiler_id,
                    'ProductDeal.end_date >' => TODAYDATE
                ), 'limit' => '5'
                )
            );
        }
        return $retailer_deal_data;
    }

// get advertise based on size and get random data
    public function get_advertise($size = null)
    {
        $advertise_data = ClassRegistry::init('Ad');
        if (empty($size)) {
            $size = "1600x1210";
        }
        $advertise_data = $advertise_data->find('first', array(
                'conditions' => array(
                    'Ad.size' => $size,
                ),
                'limit' => '1',
                'order' => 'rand()',
            )
        );
        if (isset($advertise_data) && !empty($advertise_data)) {
            $advertise_image = $this->check_image($advertise_data['Ad']['image'], 'advertise_image');

            return $advertise_data = array('image' => $advertise_image, 'title' => $advertise_data['Ad']['title'], 'link' => $advertise_data['Ad']['link']);
        }
    }

    public function WorldData($id = null)
    {
        $list = null;
        $world = ClassRegistry::init('World');
        if (!empty($id) && is_numeric($id)) {
            $list = $world->find('list', array('recursive' => -1, 'conditions' => array('World.in_location' => $id), 'fields' => array('id', 'local_name')));
        } else {
            $list = $world->find('list', array('recursive' => -1, 'conditions' => array('World.type' => 'CO', 'World.local_name IS NOT NULL'), 'fields' => array('id', 'local_name'), 'order' => array('local_name ASC')));
        }
        return $list;
    }

    public function retailer_over_all_rating($retailer_id = null, $user_id = null)
    {
        if ($user_id) {

        } else {
            $user_id = ME;
        }
        $rating_data = array();
        if ($retailer_id) {
            $rating = ClassRegistry::init('Rating');
            $rating->bindModel(array(
                    'belongsTo' => array(
                        'Product' => array(
                            'foreignKey' => false,
                            'type' => 'INNER',
                            'conditions' => array(
                                'Product.id= Rating.foreign_id',
                            ),
                        )
                    )
                )
            );
            $rating_data = $rating->find('all', array('conditions' => array('Product.user_id' => $user_id), 'fields' => array(
                '(sum(Rating.rating)  / count(Rating.id)) as avg_rating,
            (SELECT count(*) from lab_products  as Product where Product.user_id=' . $user_id . ') as total_product,
                (SELECT count(*) from lab_products  as Product INNER JOIN lab_reviews as Review On(Product.id=Review.product_id) where Product.user_id=' . $user_id . ') as total_review,
                (SELECT count(*) from lab_reservations  as Reservation where Reservation.product_retailer_id=' . $retailer_id . ') as total_reservation,
                (SELECT count(*) from lab_product_deals  as ProductDeal where ProductDeal.retailer_id=' . $retailer_id . ') as total_deal'
            )));
        }
        return $rating_data;
    }

    public function get_category_link()
    {
        $footer_category = ClassRegistry::init('FooterCategory');
        $footer_link = ClassRegistry::init('FooterLink');
        $footer_category->bindModel(array('hasMany' => array('FooterLink' => array('conditions' => array('FooterLink.status' => 1), 'order' => 'FooterLink.position ASC'))));
        $footer_category_data = $footer_category->find('all', array('conditions' => array('FooterCategory.status' => 1)));
        return $footer_category_data;
    }

    public function consumer_reservation()
    {
        $rating_data = array();
        if (ME) {
            $Reservation = ClassRegistry::init('Reservation');
            $Reservation_data = $Reservation->find('first', array('conditions' => array('Reservation.user_id' => ME), 'fields' => array(
                '(SELECT count(*) from lab_reservations as Reservation where Reservation.user_id = ' . ME . ') as total_reservation',
                '(SELECT count(*) from lab_reservations as Reservation where Reservation.user_id = ' . ME . ' AND Reservation.status =1) as pending_reservation',
                '(SELECT count(*) from lab_reservations as Reservation where Reservation.user_id = ' . ME . ' AND Reservation.status =2) as accepted_reservation',
                '(SELECT count(*) from lab_reservations as Reservation where Reservation.user_id = ' . ME . ' AND Reservation.status =3) as rejected_reservation_by_reatiler',
                '(SELECT count(*) from lab_reservations as Reservation where Reservation.user_id = ' . ME . ' AND Reservation.status =4) as rejected_by_you',
                '(SELECT count(*) from lab_reservations as Reservation where Reservation.user_id = ' . ME . ' AND Reservation.status =5) as rejected_by_admin',
            )));
        }
        return $Reservation_data;
    }

    public function left_deal_time($seller_last_login)
    {
        $current_time = date("Y-m-d h:i:s");
        $d1 = new DateTime($current_time);
        $d2 = new DateTime($seller_last_login);
        //$d2=new DateTime("2013-06-06 00:00:00");
        $interval = $d2->diff($d1);

        //Getting days difference
        $years = $interval->format("%y");
        $months = $interval->format("%m");
        $days = $interval->format("%d");

        //Getting time difference
        $hours = $interval->format("%h");
        $minutes = $interval->format("%i");
        $seconds = $interval->format("%s");

        if ($years > 0) {
            if ($years > 1) {
                echo $years . " years left";
            } else {
                echo $years . " year left";
            }
        } elseif ($months > 0) {
            if ($months > 1) {
                echo $months . " months left";
            } else {
                echo $months . " month left";
            }
        } elseif ($days > 0) {
            if ($days > 1) {
                echo $days . " days left";
            } else {
                echo $days . " day left";
            }
        } elseif ($hours > 0) {
            if ($hours > 1) {
                echo $hours . " hours left";
            } else {
                echo $hours . " hour left";
            }
        } elseif ($minutes > 0) {
            if ($minutes > 1) {
                echo $minutes . " minutes left";
            } else {
                echo $minutes . " minute left";
            }
        } else {
            if ($seconds > 1) {
                echo $seconds . " seconds left";
            } else {
                echo $seconds . " second left";
            }
        }
    }

    // function for calculate avg rating based on reservation id 
    public function avg_rating($reservation_id = null)
    {
        if ($reservation_id && is_numeric($reservation_id)) {
            $rating = ClassRegistry::init('Rating');
            $avg_rating = $rating->find('first', array('conditions' => array('Rating.reservation_id' => $reservation_id), 'fields' => array('(sum(Rating.rating)  / count(Rating.id)) as avg_rating')));
            if (isset($avg_rating[0]['avg_rating']) && !empty($avg_rating[0]['avg_rating'])) {
                return number_format($avg_rating[0]['avg_rating'], 2);
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    // get unread message
    public function getUnreadMessage()
    {
        $messageIndex = ClassRegistry::init('MessageIndex');
        $unread_message = $messageIndex->find('count', array('conditions' => array('MessageIndex.recipient_id' => ME, 'MessageIndex.is_read' => 0, 'MessageIndex.deleted' => 0)));
        return $unread_message;

    }
    // get unread message
    public function get_service_location()
    {
        $ServiceArea = ClassRegistry::init('ServiceArea');
        $ServiceType = ClassRegistry::init('ServiceType');
        $ServiceAreaData = $ServiceArea->find('all', array('conditions' => array('ServiceArea.status' => 1)));
        $ServiceTypeData = $ServiceType->find('all', array('conditions' => array('ServiceType.status' => 1)));
        $all_array = array('service_area'=>$ServiceAreaData,'service_type'=>$ServiceTypeData);
       return $all_array;


    }

    public function get_service_vehicle()
    {
        $VehicleType = ClassRegistry::init('VehicleType');
        $ServiceType = ClassRegistry::init('ServiceType');
        $VehicleTypeData = $VehicleType->find('all', array('conditions' => array('VehicleType.status' => 1)));
        $ServiceTypeData = $ServiceType->find('all', array('conditions' => array('ServiceType.status' => 1,'ServiceType.id' =>array(2,3))));
        $all_array = array('vehicle_type'=>$VehicleTypeData,'service_type'=>$ServiceTypeData);
        return $all_array;
    }

    // get unread message
    public function get_service_taxi()
    {
        $ServiceArea = ClassRegistry::init('ServiceArea');
        $ServiceType = ClassRegistry::init('ServiceType');
        $ServiceAreaData = $ServiceArea->find('all', array('conditions' => array('ServiceArea.status' => 1)));
        $ServiceTypeData = $ServiceType->find('all', array('conditions' => array('ServiceType.status' => 1,'ServiceType.id' =>array(1,2))));
        $all_array = array('service_area'=>$ServiceAreaData,'service_type'=>$ServiceTypeData);
        return $all_array;


    }

}

?>