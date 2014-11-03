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
class Passenger extends AppModel {

	// public $hasOne = array('Provider' => array('dependent' => true,));
	    public $validate = array(

        'fname' => array(
            'first_name' => array('rule' => 'notEmpty', 'message' => 'first name cannot be left blank.',),
            'pattern' => array('rule' => '/^[A-Za-z 0-9]*$/', 'message' => 'Only letters,number and space allowed'),
            'between' => array('rule' => array('between', 3, 15), 'message' => 'Between 3 to 15 characters')),
        'lname' => array(
            'pattern' => array('rule' => '/^[A-Za-z 0-9]*$/', 'message' => 'Only letters,number and space allowed'),
	     ),

	     
       
    );

}
