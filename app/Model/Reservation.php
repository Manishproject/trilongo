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
class Reservation extends AppModel {

   
	public $hasMany = array('Passenger');
    
	    public $validate = array(
	     'service_id' => array('rule' => 'notEmpty', 'message' => 'Please select service type to contine.'),
  
	      'currently_in' => array(
            'notEmpty' => array('rule' => 'notEmpty', 'message' => 'Please select your current country',),
           ),
	      'booking_in' => array(
            'notEmpty' => array('rule' => 'notEmpty', 'message' => 'Please enter valid contring to made booking',),
           ),  
        'estimated_reserved_days' => array(
            'notEmpty' => array('rule' => 'notEmpty', 'message' => 'Please Enter duration in days',),
           ),
        'pickup_location' => array(
            'notEmpty' => array('rule' => 'notEmpty', 'message' => 'Please select your pick/drop location',),
           ),   
                 
        'contact_address_1' => array(
            'notEmpty' => array('rule' => 'notEmpty', 'message' => 'Please Enter valid contact address.',),
           ),   
           
       'contact_city' => array(
            'notEmpty' => array('rule' => 'notEmpty', 'message' => 'Please Enter valid city.',),
           ),  
       'contact_state' => array(
            'notEmpty' => array('rule' => 'notEmpty', 'message' => 'Please Enter valid state.',),
           ),  
        'communication_option' => array(
            'notEmpty' => array('rule' => 'notEmpty', 'message' => 'Please select your prefer communication.',),
           ), 
                      
        'guest_email' => array(
            'notEmpty' => array('rule' => 'notEmpty', 'message' => 'Please Enter valid contact address.',),
            'email' => array('rule' => 'email', 'message' => 'Please Enter valid contact address.'),
           ),  
    );
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
