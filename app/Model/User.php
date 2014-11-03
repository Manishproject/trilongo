<?php
/**
 * Application model for CakePHP.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('AppModel', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class User extends AppModel {


	// public $hasOne = array('Provider' => array('dependent' => true,));
	    public $validate = array(
        'email' => array('email' => array('rule' => 'email', 'message' => 'Please provide a valid email address.'),
            'isUnique' => array('rule' => 'isUnique', 'message' => 'Email address already in use.')),
        'first_name' => array(
            'first_name' => array('rule' => 'notEmpty', 'message' => 'first name cannot be left blank.',),
//            'pattern' => array('rule' => '/^[A-Za-z 0-9]*$/', 'message' => 'first name Only letters,number and space allowed'),
            'between' => array('rule' => array('between', 3, 15), 'message' => 'First name Between 3 to 15 characters')),
        'last_name' => array(
//            'pattern' => array('rule' => '/^[A-Za-z 0-9]*$/', 'message' => 'Last name Only letters,number and space allowed in last name '),
	     ),
	    
        'username' => array('alphaNumeric' => array('rule' => 'alphaNumeric', 'message' => ' User name Only letters and number allowed in username'), 'isUnique' => array('rule' => 'isUnique', 'message' => 'User Name already in use.')),
        'password' => array('rule' => 'notEmpty', 'message' => 'Please enter password.'),
        'confirm_password' => array(
            'non_empty' => array('rule' => 'notEmpty', 'message' => 'Please enter confirm  password.'),
            'match' => array('rule' => array('identicalFieldValues', 'password'), 'message' => 'Password does not match.'),
        ),
        'current_pass' => array(
            'non_empty' => array('rule' => 'notEmpty', 'message' => 'Please enter current  password.'),
        ),
        
//        'phone' => array('mobile' => array('rule' => array('phone', '/^[0-9]( ?[0-9]){8} ?[0-9]$/'),
//             'message' => 'Please enter a valid phone eg. 5556668888', 'allowEmpty' => true),
//              'isUnique' => array('rule' => 'isUnique', 'message' => 'Mobile number already in use.'
//        )),
            'phone' => array( 'isUnique' => array('rule' => 'isUnique', 'message' => 'Mobile number already in use.')),
        
    );

    function identicalFieldValues($field = array(), $compare_field = null) {
        foreach ($field as $key => $value) {
            $v1 = $value;
            $v2 = $this->data[$this->name][$compare_field];
            if ($v1 !== $v2) {
                return FALSE;
            } else {
                continue;
            }
        }
        return TRUE;
    }
    

    
    public function beforeSave($options = array()) {
        if (isset($this->data[$this->name]['password'])) {
        	
        //    $this->data['User']['password'] =md5($this->data['User']['password']);
        }

        
        if (!empty($this->data[$this->name]['email'])) {
            $this->data[$this->name]['email'] = strtolower($this->data[$this->name]['email']);
        }
        
      if (!isset($this->data[$this->name]['username']) && isset($this->data['User']['email'])) {
            $this->data['User']['username'] =$this->generate_username($this->data['User']['email']);
        }
        return true;
    }
    
	function check_email($email){
	  return $this->find('count',array('conditions'=>array('User.email'=>$email)));
	}
	
	function check_username($username){
		return $this->find('count',array('conditions'=>array('User.username'=>$username)));	
	}
	
	function generate_username($email){
	 $i = 0;	
	 $user_name =(filter_var($email,FILTER_VALIDATE_EMAIL))?strstr($email, '@', true):$email;
	 $user_name_new =$user_name;
	
    while(1){
    	
    	if($this->check_username($user_name_new)){ 
    	 $i++;
    	$user_name_new = $user_name.'_'.$i;
    	}else{
    		return $user_name_new;
    	}
      }
	 
	}
	
 function generateKey(){
		$key= substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 40);
		return $key.'-'.time();
	}
	
function credit($uid,$amount){
	$balance = $this->field('balance',array('User.id'=>$uid));
	 $balance  =$balance +$amount;
    $balance_info['User']['id'] = $uid;
    $balance_info['User']['balance'] = $balance;

	if($this->save($balance_info)){
        return true;
    }else{
        return false;
    }

}
    function unbindModelAll() {
        foreach (array(
                     'hasOne' => array_keys($this->hasOne),
                     'hasMany' => array_keys($this->hasMany),
                     'belongsTo' => array_keys($this->belongsTo),
                     'hasAndBelongsToMany' => array_keys($this->hasAndBelongsToMany)) as $relation => $model) {
            $this->unbindModel(array($relation => $model));
        }
    }
	
}
