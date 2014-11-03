<?php
/**
 * User controller.
 */
App::uses('AppController', 'Controller');

class UsersController extends AppController
{

    public $components = array('Cookie', 'Session', 'RequestHandler', 'Auth', 'FileHandler', 'DATA');
    public $uses = array('User');

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow( 'send_app_link', 'curl_file_get_contents', 'hotmail', 'hotmail_new', 'login', 'signup', 'activate', 'activated', 'resetpass', 'agent_signup', 'forgot_password', 'state', 'feedback' );
    }

    public function index()
    {
        $this->redirect('/user');
    }

    /* This function can be used for public profile */

    public function view($id = NULL)
    {

    }

    public function admin_logout()
    {
        $this->Session->setFlash('Good-Bye');
        return $this->redirect($this->Auth->logout());
    }


    /**
     *
     * list all users for admin ...
     */
    public function admin_index()
    {
        $condition = array();
        if (isset($this->request->data['search']) && !empty($this->request->data['search'])) {
            $search = $this->request->data['search'];

            $condition[] = array(
                'or' => array(
                    "User.first_name LIKE" => "%$search%",
                    "User.last_name LIKE" => "%$search%"
                ));
        }


        $this->paginate = array(
            'limit' => 20,
            'conditions' => $condition,
            'order' => array('User.id' => 'DESC')
        );
        $data = $this->paginate('User');
        $this->set('data', $data);
    }

    public function admin_login()
    {

        $this->layout = "admin_login";
        if ($this->Auth->loggedIn()) {
            $this->redirect(array('controller' => 'labs', 'action' => 'index'));
        } else if ($this->request->is('post')) {

            $email = $this->data['User']['email'];
            $password = md5($this->data['User']['password']);
            $conditions = array(
                'or' => array('User.email' => $email),
                'User.password' => $password,
                'User.role' => ADMIN_ROLE, // admin
            );
            $isUserAdmin = $this->User->find("first", array("conditions" => $conditions));

            if (empty($isUserAdmin)) {
                $this->Session->setFlash(__('Invalid Email or Password'), 'default', array(), 'error');
            } else {
                if ($isUserAdmin['User']['status'] == 1) {
                    if ($this->Auth->login($isUserAdmin['User'])) {
                        return $this->redirect(array('controller' => 'labs', 'action' => 'index', 'admin' => true));
                    } else {
                        $this->Session->setFlash(__('Error Occured. Please try again later.'), 'default', array(), 'error');
                    }
                } else {
                    $msg = ($isUserAdmin['User']['status'] == 2) ? 'Your account has been deleted.' : 'Your account has been blocked';
                    $this->Session->setFlash(__($msg), 'default', array(), 'error');
                }
            }
        }
    }

    public function adminlogout()
    {


        $this->Session->destroy();
        $this->Cookie->delete('Auth.User');
        $this->redirect(array('controller' => 'admin'));
    }

    public function login()
    {
        $this->set('title_for_layout', 'Login');


        if (isset($_GET['return_url'])) {
            $return_url = urldecode($_GET['return_url']);
            $this->Session->write('return_url_session', $return_url);
        }

        if ($this->Auth->User()) {
            $this->redirect(SITEURL);
        }

        if ($this->request->is('post')) {

            $email = $this->data['User']['email'];
            $password = md5($this->data['User']['password']);
            $conditions = array(
                'OR' => array(
                    array('User.role' => 2),
                    array('User.role' => 3)
                ),
                'User.password' => $password,
                'User.email' => $email,
            );
            $User = $this->User->find("first", array("conditions" => $conditions));
            if (empty($User)) {
                $this->Session->setFlash(__('Invalid Email or Password'), 'default', array('class' => 'message error'), 'logmsg');
            } else {

                if ($User['User']['status'] == 1) {
                    if ($this->Auth->login($User['User'])) {
                        if ($this->Auth->User('role') == 2) {
                            return $this->redirect(array('controller' => 'providers', 'action' => 'my_account', 'admin' => false));
                        } elseif ($this->Auth->User('role') == 3) {
                            return $this->redirect(array('controller' => 'riders', 'action' => 'my_account', 'admin' => false));
                        }
                    } else {
                        $this->Session->setFlash(__('Error Occcured. Please try again later.'), 'default', array('class' => 'message error'), 'logmsg');
                    }
                } else {
                    $msg = ($User['User']['status'] == 2) ? 'Your account has been deleted.' : 'Your account has been blocked';
                    $this->Session->setFlash(__($msg), 'default', array('class' => 'message error'), 'logmsg');
                }
            }
        }
    }

    public function logout()
    {

        $this->Session->destroy();
        $this->Cookie->delete('Auth.User');
        $this->redirect('/');
    }

    public function forgot_password()
    {
        $this->set('title_for_layout', 'Forgot Passowrd');
        if ($this->Auth->user() != "") {
            $this->redirect(SITEURL);
        } else {
            if ($this->request->is('post')) {
                if (isset($this->request->data['User']['email']) && $this->request->data['User']['email']) {
                    $users = $this->User->find('first', array(
                        'recursive' => -1,
                        'conditions' => array('User.email' => $this->request->data['User']['email'], 'User.status <>' => 4),
                    ));

                    if (!empty($users)) {
                        if ($users['User']['status'] != 1) {
                            if ($users['User']['status'] == 0) {
                                $this->Session->setFlash(__("Your account has been blocked.Please contact to admin."), 'default', array("class" => "message error"), 'logmsg');
                            } else {
                                $this->Session->setFlash(__("Your account not exist.Please contact to admin."), 'default', array("class" => "message error"), 'logmsg');
                            }
                            $this->redirect(array('controller' => 'users', 'action' => 'forgot_password'));
                        } else {
                            $id = $users['User']['id'];
                            if ($users['User']['email'] == $this->request->data['User']['email']) {
                                $key = $this->generateKey();
                                $this->User->set('id', $id);
                                $this->User->saveField('activation_key', $key);
                                $link = SITE_URL . "users/resetpass/" . $id . "/" . $key;
                                $arr = array('USERNAME' => ucfirst($users['User']['first_name']), 'LINK' => $link);
                                $this->DATA->AppMail($users['User']['email'], 'ForgotPassword', $arr);
                                $this->Session->setFlash("Your password and further instruction has been send to your email address.Please Check your email.", 'default', array('class' => 'message success'), 'logmsg');
                                $this->redirect(array('controller' => 'users', 'action' => 'login'));
                            }
                        }
                    } else {
                        $this->Session->setFlash(__("Sorry no account accociated with this email address"), 'default', array('class' => 'message error'), 'logmsg');
                        $this->redirect(array('controller' => 'users', 'action' => 'forgot_password'));
                    }
                } else {
                    $this->Session->setFlash(__("Email field is required."), 'default', array('class' => 'message error'), 'logmsg');
                    $this->redirect(array('controller' => 'users', 'action' => 'forgot_password'));
                }
            }
        }
    }

    /*
     * Normal user register
     * */

    public function signup()
    {
        $this->set('title_for_layout', 'User Registration');
        if ($this->Auth->User()) {
            $this->redirect(SITEURL);
        }
        $this->User->set($this->data);
        $r1 = $this->User->validates();
        if ($r1 && !empty($this->data)) {
            $key = $this->generateKey();
            $this->request->data['User']['status'] = 1;
            $this->request->data['User']['password'] = md5($this->request->data['User']['password']);
            $this->request->data['User']['role'] = GUEST_ROLE;
            $this->request->data['User']['activation_key'] = $key;

            if ($this->User->save($this->data)) {
                $lid = $this->User->id;
                $arr = array('USERNAME' => ucfirst($this->request->data['User']['first_name']));
                $this->DATA->AppMail($this->request->data['User']['email'], 'WelcomeUser', $arr);
                //login the account
                $User = $this->User->find("first", array("conditions" => array('User.id' => $lid)));
                if ($this->Auth->login($User['User'])) {
                    $this->Session->setFlash('Your account has been registered.', 'default', array('class' => 'message success'), 'logmsg');
                    return $this->redirect(array('controller' => 'riders', 'action' => 'my_account', 'admin' => false));
                } else {
                    $this->Session->setFlash('Please login something is wrong.', 'default', array('class' => 'message success'), 'logmsg');
                }
            } else {
                $this->Session->setFlash(__('Error occured!!!. Pleaese try again.'), 'default', array('class' => 'message error'), 'logmsg');
            }
        }
    }

    private function generateKey()
    {
        $key = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 40);
        return $key . '-' . time();
    }

    public function activate($uid = NULL, $key = NULL)
    {
        $this->autoRender = false;
        if ($uid && $key) {
            $key1 = explode('-', $key);
            $sent_time = isset($key1[1]) ? $key1[1] : '';
            $userData = $this->User->find('first', array('recursive' => -1, 'conditions' => array('User.id' => (int)$uid, 'activation_key' => $key)));
            if ($userData) {
                if ($userData['User']['status'] == 1) {
                    $this->Session->setFlash('Your account is already activated.Enjoy it', 'default', array('class' => 'message error'), 'logmsg');
                    $this->redirect(array('controller' => 'users', 'action' => 'login'));
                } else if ($userData['User']['status'] == 0) {
                    $user_info = array();
                    $user_info['User']['id'] = $userData['User']['id'];
                    $user_info['User']['status'] = 1;
                    if ($this->User->save($user_info)) {
                        $this->Session->setFlash('Your account is activated  succsfully.Enjoy it', 'default', array('class' => 'message success'), 'logmsg');
                        $this->redirect(array('controller' => 'users', 'action' => 'login'));
                    } else {
                        $this->Session->setFlash('Please try again', 'default', array('class' => 'message error'), 'logmsg');
                        $this->redirect(array('controller' => 'users', 'action' => 'login'));
                    }
                }
            }
        } else {
            $this->Session->setFlash('Something wnet wrong.Please try again', 'default', array('class' => 'message error'), 'logmsg');
            $this->redirect(array('controller' => 'users', 'action' => 'login'));
        }
    }

    public function resetpass($uid = NULL, $key = NULL)
    {
        $this->set('title_for_layout', 'Update Password');
        if ($uid && $key) {
            $key1 = explode('-', $key);
            $sent_time = isset($key1[1]) ? $key1[1] : '';
            $this->set('uid', $uid);
            $this->set('key', $key);
            $userData = $this->User->find('first', array('recursive' => -1, 'conditions' => array('User.id' => (int)$uid, 'activation_key' => $key)));
            if ($userData) {
                if ($this->request->is('post')) {
                    $password = $this->request->data['User']['password'];
                    $new_password = $this->request->data['User']['confirm_password'];
                    if ($password == $new_password) {
                        $user_info['User']['id'] = $userData['User']['id'];
                        $user_info['User']['password'] = md5($new_password);
                        $user_info['User']['activation_key'] = $this->generateKey();
                        if ($this->User->save($user_info)) {
                            $this->Session->setFlash('Password has been change successfully.', 'default', array('class' => 'message success'), 'logmsg');
                            $this->redirect(array('action' => 'login'));
                        }
                    } else {
                        $this->Session->setFlash('Confirm password and password not match', 'default', array('class' => 'message error'), 'logmsg');
                        $this->redirect(array('action' => 'resetpass', $uid, $key));
                    }
                }
            } else {
                $this->Session->setFlash('Token is expired.Please try again', 'default', array('class' => 'message error'), 'logmsg');
                $this->redirect(array('action' => 'login'));
            }
        } else {
            $this->redirect('/');
        }

    }

    public function change_pass()
    {
        $user_id = $this->login_user_id;

        $force_change_pass = false;

        if ($this->Session->read('force_change_pass')) {
            $force_change_pass = true;
        }


        if ($this->request->is('post')) {
            $this->User->set($this->request->data);
            $validateCore = $this->User->validates();
            $curr_pass_prev = $this->Auth->user('password');
            $validateCust = true;
            $new_password = $this->request->data['User']['password'];
            if (!$force_change_pass) {
                if ($curr_pass_prev != md5($this->request->data['User']['current_pass'])) {
                    $validateCust = false;
                    $this->Session->setFlash('Incorrect current password', 'default', array(), 'error');
                }
            }

            if ($validateCore && $validateCust) {
                $this->User->set('id', $user_id);
                if ($this->User->SaveField('password', $new_password)) {
                    $this->Session->setFlash('Password has been saved.', 'default', array(), 'success');
                    $this->Session->delete('force_change_pass');
                    $this->redirect(array('action' => 'myaccount'));
                }
            }
        }


        $this->set('force_change_pass', $force_change_pass);
    }

    public function activated()
    {
        $user_id = $this->login_user_id;
        if ($this->Session->read('account_activated')) {
            $this->Session->delete('account_activated');
        } else {
            $this->redirect('/');
        }
    }

    public function agent_signup()
    {
        $this->set('title_for_layout', 'Provider Sign up');
        if ($this->Auth->User()) {
            $this->redirect(SITEURL);
        }
        $this->User->set($this->data);
        $r1 = $this->User->validates();

        if ($r1 && !empty($this->data)) {
            $this->request->data['User']['status'] = 0; // ,3-In Registration
            $this->request->data['User']['role'] = 3;
            $password = $this->request->data['User']['password'];
            $confirm_password = $this->request->data['User']['confirm_password'];
            $this->request->data['User']['password'] = md5($this->request->data['User']['password']);
            $this->request->data['User']['confirm_password'] = md5($this->request->data['User']['confirm_password']);
            if ($this->User->save($this->data)) {
                $lid = $this->User->getLastInsertId();
                $this->loadModel('Provider');
                $provider_data = array();
                $provider_data['Provider']['user_id'] = $lid;
                if ($this->Provider->save($provider_data)) {
                    $key = $this->generateKey();
                    $this->User->saveField('activation_key', $key);
                    $link = SITE_URL . 'users/activate/' . $lid . '/' . $key;
                    $arr = array('USERNAME' => ucfirst($this->request->data['User']['first_name']), 'ACTIVATATIONLINK' => $link);
                    $this->DATA->AppMail($this->request->data['User']['email'], 'ProviderRegistration', $arr);
                    $this->Session->setFlash('Your activation link has been sent to your email. Please check your inbox and activate your account to continue.', 'default', array('class' => 'message success'), 'logmsg');
                    $this->redirect(array('controller' => 'users', 'action' => 'agent_signup'));
                } else {
                    $this->Session->setFlash('Sorry something went wrong', 'default', array('class' => 'message error'), 'logmsg');
                }
            } else {
                $this->request->data['User']['password'] = $password;
                $this->request->data['User']['confirm_password'] = $confirm_password;
                $this->Session->setFlash(__('Error occured!!!. Pleaese try again.'), 'default', array('class' => 'message error'), 'logmsg');
            }
        }
    }

    function confirmation($role = 'guest')
    {

        if (!in_array($role, array('guest', 'provider'))) {
            $this->redirect('/');
        }
        $this->set('role', $role);
    }


    // function for get state based on country_id

    public function state()
    {
        $this->autoRender = false;
        $state_list = $this->Deem->WorldData($this->data['id']);
        echo json_encode($state_list);
        die;
    }


    // feedback
    public function feedback()
    {
        if ($this->request->is('ajax')) {
            if ($this->request->is('post') || $this->request->is('put')) {
                $result = array();
                $result['status'] = 0;

                $email = $this->request->data['Feedback']['email'];
                $name = $this->request->data['Feedback']['name'];
                $message = $this->request->data['Feedback']['message'];
                if (!empty($email) && !empty($name) && !empty($message)) {
                    // check email is valid or not
                    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $arr = array('EMAIL' => $email, 'NAME' => ucfirst($name), 'MESSAGE' => $message);
                        $this->DATA->AppMail(ADMIN_EMAIL, 'FeedbackResult', $arr);
                        $result['message'] = "Your message has been sent!";
                        $result['status'] = 1;
                    } else {
                        $result['message'] = "Please fill valid email address";
                    }

                } else {
                    $result['message'] = "Please fill all the fields";
                }
                echo json_encode($result);
                die;
            }
        }
    }


    //  send sms app link
    public function send_app_link()
    {
        $this->autoRender = false;
        $phone_number = $this->request->data['SendAppLink']['phone_number'];
        if ($phone_number && $this->request->is('ajax')) {

            // create message text
            $message =  $this->DATA->create_message_text('AppLink', array());
            $people = array($phone_number => $message);
            $message_response =  $this->DATA->SendSMS($people);
            $result['status'] = $message_response['status'];
            $result['message'] = $message_response['message'];
            echo json_encode($result);die;
        }else{
           $this->redirect(SITE_URL);
        }
    }


}

?>