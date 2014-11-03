<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
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
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
//http://www.textmarks.com/api/send-text-messages/
App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package        app.Controller
 * @link        http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{
    public $components = array('Cookie', 'Deem', 'Auth', 'Session', 'EmailTemplate.Mailer', 'DATA');
    public $helpers = array('Html', 'Form', 'JqueryEngine', 'Session', 'Text', 'Time', 'Paginator', 'Image');
    public $uses = array('Taxonomy.Term');
    public $type_of_service;
    public $login_user_id;
    public $login_user;
    public $login_user_detail;

    public function beforeFilter()
    {
        parent::beforeFilter();


        // database find facebook and twitter link
        $this->loadModel('Setting');
        $setting_all_data = $this->Setting->find("all");
        if (isset($setting_all_data) && !empty($setting_all_data)) {
            foreach ($setting_all_data as $setting_data) {
                if (!defined($setting_data['Setting']['slug'])) {
                    define($setting_data['Setting']['slug'], $setting_data['Setting']['value']);
                }
            }
        }


        $current_user = $this->Auth->user();
        $this->set('current_user', $current_user); // this variable can be used inside view/element


        // get current city of user based on ip and set in cookies
        $session = $this->Session->read('current_location');
        $location_based_ip = "";
        if (empty($session)) {
//            $client_ip = $this->DATA->get_client_ip();
            $location_based_ip = $this->DATA->getLocationInfoByIp($_SERVER['REMOTE_ADDR']);
        }
        if (!empty($session)) {
            $country_code = $session[0];
            $country_name = $session[1];
        } elseif (!empty($location_based_ip)) {


            $country_code = $location_based_ip[0];
            $country_name = $location_based_ip[1];
            $country_code_name = array($country_code, $country_name);
            $this->Session->write('current_location', $country_code_name);
        } else {
            $country_code = "IN";
            $country_name = "India";
        }

        $this->set('country_code', $country_code);
        $this->set('country_name', $country_name);

        // get country name in the database and set into session
        if ($this->Session->read('country_array') == false) {
            $country_array = $this->DATA->WorldData_name();
            if (isset($country_array) && !empty($country_array)) {
                $this->Session->write('country_array', $country_array);
                $this->set('country_array', $country_array);
            }
        } else {
            $country_array = $this->Session->read('country_array');
            $this->set('country_array', $country_array);
        }


        if ($this->params['prefix'] == 'admin') {

            $this->layout = 'admin';
            if ($this->Auth->loggedIn()) {
                if ($this->Auth->user('role') != ADMIN_ROLE) {
                    $this->layout = 'default';
                    $this->redirect('/');
                }
            }
        }
        if ($this->Auth->User('id') != "") {
            if (!defined('ME')) {
                define("ME", $this->Auth->User('id'));
            }
            if (!defined('ROLE')) {
                define("ROLE", $this->Auth->User('role'));
            }
            $this->loadModel('User');
            if ($this->Auth->User('role') == 3) {
                $this->User->bindModel(array('hasOne' => array('Provider')));
            }
            $this->login_user_detail = $login_user_detail = $this->User->find("first", array('conditions' => array('User.id' => ME)));

            $this->set('login_user_detail', $login_user_detail);
            if ($this->Auth->User('role') == 3) {
                if (!defined('PROVIDERID')) {
                    define("PROVIDERID", $login_user_detail['Provider']['id']);
                }
            }


            if ($this->Session->check('return_url_session') == true) {
                $redirect = $this->Session->read('return_url_session');
                $this->Session->delete('return_url_session');
                $this->redirect($redirect);
            }


        } else {
            if (!defined('ME')) {
                define("ME", null);
            }
            if (!defined('ROLE')) {
                define("ROLE", null);
            }
        }

        if (empty($this->params->url)) $this->layout = 'home';

        $bookingtype = array( // User at time of registration
            'individual' => 'Individual',
            'family' => 'Family',
            'group' => 'Group',
            'students' => 'Students',
            'religious_organization' => 'Religious Organization',
        );

        $this->set('bookingtype', $bookingtype);


        $type_of_service = $this->Term->find('list',
            array('recursive' => -1,
                'conditions' => array('Term.vocabulary_id' => 1)
            ));
        $this->type_of_service = $type_of_service;


        $this->set('type_of_service', $type_of_service);

    }

}

