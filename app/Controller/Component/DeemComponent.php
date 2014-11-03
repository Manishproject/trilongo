<?php
/**
 * 
 *component for all custom function and sms api...
 * @author deemtech
 *
 */
class DeemComponent extends Component{
    public $uses =array('Reservation','User'); 	
	
	/**
	 * Use to change currenty
	 */
 function getCurrencyExchangeRate($from,$to,$decode =true){
      $url = 'http://rate-exchange.appspot.com/currency?from='.$from.'&to='.$to;
      $content = file_get_contents($url);
      
      if($decode){
      $content = json_decode($content);
      }
      return $content;
  }	
  
 function getMapLocation($address,$decode =true){
 	 $address = str_replace(" ", "+", $address);
 	
      $url = 'http://maps.google.com/maps/api/geocode/json?address='.$address;
      $content = file_get_contents($url);
      
      if($decode){
      $content = json_decode($content);
      }
      return $content;
  }	

 function updateReservation(){
 } 
 
 function saveInvite($rid,$users){
  }
 
 function getProviders(){
 //(618) 912-1282
 }


    public function WorldData($id = null) {
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


    // function for move upload photo in folder
    function move_photo($image_array,$variable_name,$folder_name){
        if ($image_array[$variable_name]['error'] == 0 && !empty($image_array[$variable_name]['name'])) {
            $id = $folder_name."_" . rand(1000, 999999);
            $file_ext = @end(explode(".", $image_array[$variable_name]['name']));
            $product_image_name = $id . "." . $file_ext;
            $product_image_path = WWW_ROOT . 'data/'.$folder_name.'/' . $product_image_name;
            if (move_uploaded_file($image_array[$variable_name]['tmp_name'], $product_image_path)) {

            }
        }
    }
 
 
}
