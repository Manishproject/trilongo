<?php

App::uses('Sanitize', 'Utility', 'AppController', 'Controller');

class LabsController extends AppController {

    var $uses = array('User');
    var $components = array('Auth', 'Session', 'Email', 'RequestHandler', 'Paginator', 'DATA');
    public $helpers = array('Html', 'Form', 'JqueryEngine', 'Session', 'Text', 'Time', 'Paginator', 'Lab', 'Image');

    function beforeFilter() {
        AppController::beforeFilter();
        if ($this->Auth->User('role') != 1) {
            unset($this->params['prefix']);
            $this->redirect('/');
        }
    }

    function admin_index() {
        $this->set('title_for_layout', 'Dashboard');
        $this->loadModel('Mail');
        $this->set('mail', $this->Mail->find('count'));
    }

    public function admin_create_new_user($id = null) {
        $this->set('title_for_layout', 'Create new users');
        if ($this->request->is('get')) {
            if (!empty($id)) {
                $data = $this->User->findById($id);
                if (empty($data)) {
                    $this->layout = '404';
                } else {
                    $this->request->data = $data;
                }
            }
        } else {
            $password = $this->request->data['User']['password'];
            $confirm_password = $this->request->data['User']['confirm_password'];
            if (!empty($this->request->data)) {

                $this->request->data['User']['status'] = 1;
                $this->request->data['User']['password'] = md5($this->request->data['User']['password']);
                $this->request->data['User']['confirm_password'] = md5($this->request->data['User']['confirm_password']);
                $this->User->set($this->request->data);
                $r1 = $this->User->validates();
                if ($r1) {
                    if ($this->User->save($this->request->data)) {
// check register user is admin or normal user then send email that user 
                        if ($this->request->data['User']['role'] == 1) {
                            $arr = array('USERNAME' => $this->request->data['User']['username'], 'EMAIL' => $this->request->data['User']['email'], 'PASSWORD' => $password);
                            $this->DATA->AppMail($this->request->data['User']['email'], 'AdminCreateOtherAdmin', $arr);
                        } else if ($this->request->data['User']['role'] == 3) {
                            $arr = array('USERNAME' => $this->request->data['User']['username'], 'EMAIL' => $this->request->data['User']['email'], 'PASSWORD' => $password);
                            $this->DATA->AppMail($this->request->data['User']['email'], 'AdminCreateUser', $arr);
                        }

                        $this->Session->setFlash('User info has been save successfully', 'default', array('class' => 'message success'), 'msg');
                        $this->redirect(array('action' => 'all_user', 'admin' => true));
                    } else {
                        $this->Session->setFlash('Not able to registered', 'default', array('class' => 'message error'), 'msg');
                    }
                } else {
                    // if user choose country then fill all state 
//                    $state_list = $this->
                    $state_list_all = array();
                    if (isset($this->request->data['User']['country_id']) && !empty($this->request->data['User']['country_id'])) {
                        $state_list = $this->DATA->WorldData($this->request->data['User']['country_id']);
                        $state_list_all = array_merge(array("0" => "Select State"), $state_list);
                    }
                    $this->set('state_list_all', $state_list_all);




                    $this->request->data['User']['confirm_password'] = $confirm_password;
                }
            }
        }
    }

    public function admin_edit_user($id = null) {
        $this->set('title_for_layout', 'Edit users');
        if ($id) {
            if ($this->request->is('get')) {
                if (!empty($id)) {
                    $data = $this->User->findById($id);
                    if (empty($data)) {
                        $this->layout = '404';
                    } else {
                        $this->request->data = $data;
                    }
                }
            } else {
                if ($this->request->is('post') || $this->request->is('put')) {
                    if (!empty($this->request->data)) {
                        $this->User->set($this->request->data);
                        $r1 = $this->User->validates();
                        if ($r1) {
                            if ($this->User->save($this->request->data)) {
                                $this->Session->setFlash('User info has been updated successfully', 'default', array('class' => 'message success'), 'msg');
                                $this->redirect(array('action' => 'all_user', 'admin' => true));
                            } else {
                                $this->Session->setFlash('Not able to registered', 'default', array('class' => 'message error'), 'msg');
                            }
                        }
                    }
                }
            }
        } else {
            $this->layout = '404';
        }
    }

// fucntion for view full profile 
    public function admin_view_profile_user($id = null) {
        $this->set('title_for_layout', 'Edit users');
        if ($id) {
            if (!empty($id)) {
                $data = $this->User->findById($id);
                if (empty($data)) {
                    $this->layout = '404';
                } else {
                    $this->request->data = $data;
                    $this->set('data', $data);
                }
            }
        } else {
            $this->layout = '404';
        }
    }

    public function admin_users_review($id = null) {
        if (!empty($id)) {
            $data = $this->User->find('first', array('recursive' => -1, 'conditions' => array('User.id' => $id)));
            if (!empty($data)) {
                $this->set('data', $data);
            } else {
                $this->layout = '404';
            }
        } else {
            $this->layout = '404';
        }
    }

    public function admin_all_user() {

        $this->set('title_for_layout', 'All users');
        $this->loadModel('User');

        $a = array(1, 3);
        $this->User->unbindModelAll();
        $this->paginate = array('recursive' => 1,
            'limit' => 100,
            'conditions' => array('User.role NOT' => $a, 'User.status <>' => 6),
            'order' => array('User.id' => 'ASC'));
        $data = $this->paginate('User');

        $this->set('users', $data);
    }

// all admin user 
    public function admin_all_admin_account() {

        $this->set('title_for_layout', 'All users');
        $this->loadModel('User');

        $a = array(1);
        $this->User->unbindModelAll();
        $this->paginate = array('recursive' => 1,
            'limit' => 100,
            'conditions' => array('User.role' => $a, 'User.id <>' => 1),
            'order' => array('User.id' => 'ASC'));
        $data = $this->paginate('User');
        $this->set('users', $data);
    }

// admin delete user 
// all admin user 
    public function admin_delete_user($user_id) {
        $this->autoRender = false;
        $this->loadModel('User');
        $this->User->unbindModelAll();
        $user_data = $this->User->find('first', array('conditions' => array('User.id' => $user_id)));
// delete user image 
        if (isset($user_data) && $user_data['User']['role'] == 1) {
            $this->User->id = $user_id;
            if ($this->User->delete()) {
                $this->Session->setFlash(__('Admin account  is deleted successfully.'), 'default', array('class' => 'message success'), 'msg');
                $this->redirect(array('action' => 'all_admin_account', 'admin' => true));
            } else {
                $this->Session->setFlash(__('Sorry User is not deleted.Please try again'), 'default', array('class' => 'message error'), 'msg');
                $this->redirect(array('action' => 'all_admin_account', 'admin' => true));
            }
        } else if (isset($user_data) && $user_data['User']['role'] == 2) {
            $user_info = array();
            $user_info['id'] = $user_data['User']['id'];
            $user_info['status'] = 6;
            if ($this->User->save($user_info)) {
                $this->Session->setFlash(__('User is deleted successfully.'), 'default', array('class' => 'message success'), 'msg');
                $this->redirect(array('action' => 'all_user', 'admin' => true));
            } else {
                $this->Session->setFlash(__('Sorry User is not deleted.Please try again'), 'default', array('class' => 'message error'), 'msg');
                $this->redirect(array('action' => 'all_user', 'admin' => true));
            }
        }
    }

    public function admin_users_search() {
        $this->layout = false;
        if (isset($this->data) && !empty($this->data)) {
            $searchData = trim($this->data['mssg']);
            $a = array(1, 3);
            $cond = array();

            $this->User->unbindModelAll();

            $cond[] = array('User.role NOT' => $a,);
            if (!empty($searchData)) {
                $cond[] = array(
                    'or' => array(
                        "User.first_name LIKE" => "%" . $searchData . "%",
                        "User.last_name LIKE" => "%" . $searchData . "%",
                        "User.id" => $searchData,
                        "User.email LIKE" => "%" . $searchData . "%",
                        "User.username LIKE" => "%" . $searchData . "%",
                        "User.last_name LIKE" => "%" . $searchData . "%"
                ));
            }

            $this->paginate = array('recursive' => -1,
                'conditions' => array($cond, 'User.role NOT' => $a, 'User.status <>' => 6),
                'order' => array('User.id' => 'ASC'),
                'limit' => 100);
            $data = $this->paginate('User');
            $this->set('users', $data);
        }
    }

    public function admin_users_update() {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            if (isset($this->data) && !empty($this->data)) {
                $id = trim($this->data['uid']);
                $ty = $this->data['type'];
                $this->User->unbindModelAll();

                if ($ty == 1) {
                    $this->User->updateAll(array('User.status' => 1), array('User.id' => $id));
                    $ab = '<a onclick="change_status(' . $id . ',0)" href="javascript:void(0);"><i class="icon-remove"></i> <span> Deactivate</span> </a>';
                    echo "<script>$('#st_" . $id . "').html('Active'); $('#sp_" . $id . "').html('" . $ab . "');  </script>";
                } elseif ($ty == 0) {
                    $this->User->updateAll(array('User.status' => 0), array('User.id' => $id));
                    $ab = '<a onclick="change_status(' . $id . ',1)" href="javascript:void(0);"><i class="icon-remove"></i> <span> Active</span> </a>';
                    echo "<script>$('#st_" . $id . "').html('Deactivate'); $('#sp_" . $id . "').html('" . $ab . "');  </script>";
                }
            }
        }
    }

    public function admin_subscriptions($id = null) {
        $this->set('title_for_layout', 'Subscriptions Plan');
        $this->loadModel('Subscription');

        if ($this->request->is('get')) {
            if (!empty($id)) {

                $data = $this->Subscription->findById($id);
                if (empty($data)) {
                    $this->layout = '404';
                } else {
                    $this->request->data = $data;
                }
            }
        } else {
            if (!empty($this->request->data)) {

                if ($this->Subscription->save($this->request->data)) {
                    $this->Session->setFlash('<div class="alert alert-success">
									<button data-dismiss="alert" class="close"></button>
									<strong>Success!</strong> Subscriptions Plan has been added.</div>', 'default', array('class' => 'ab'), 'msg');
                    $this->redirect($this->referer());
                } else {
                    $this->Session->setFlash('<div class="alert alert-error">
									<button data-dismiss="alert" class="close"></button>
									<strong>Error!</strong> Not able to save.</div>', 'default', array('class' => 'ab'), 'msg');
                }
            }
        }

        $this->paginate = array('recursive' => 1, 'limit' => 100, 'order' => array('Subscription.id' => 'DESC'));
        $data = $this->paginate('Subscription');
        $this->set('all', $data);
    }

    public function admin_updated_subscriptions_status() {
        $this->autoRender = false;
        $this->loadModel('Subscription');

        if (isset($this->data) && !empty($this->data)) {
            $date = DATE;
            $id = trim($this->data['mssg']);
            $data = $this->Subscription->find('first', array('conditions' => array('Subscription.id' => $id), 'fields' => array('Subscription.id', 'Subscription.status')));
            if (!empty($data)) {
                if ($data['Subscription']['status'] == 1) {
                    $st = 0;
                } elseif ($data['Subscription']['status'] == 0) {
                    $st = 1;
                }

                if ($this->Subscription->updateAll(array('Subscription.status' => $st, 'Subscription.updated' => "'$date'"), array('Subscription.id' => $id))) {
                    if ($st == 0) {
                        echo "Inactive";
                    } elseif ($st == 1) {
                        echo "Active";
                    }
                }
            }
        }
    }

    public function admin_new_retailer_request() {

        $this->set('title_for_layout', 'New Retailer Request');
        $this->loadModel('Retailer');
        $this->paginate = array('recursive' => 1,
            'limit' => 100,
            'conditions' => array('Retailer.status' => 0),
            'order' => array('Retailer.created' => 'DESC'));
        $data = $this->paginate('Retailer');
        $this->set('all', $data);
    }

    public function admin_all_retailer() {

        $this->set('title_for_layout', 'All Retailer\'s');
        $this->loadModel('Retailer');
        $this->paginate = array('recursive' => 1,
            'limit' => 100,
            'conditions' => array(),
            'order' => array('Retailer.created' => 'ASC'));
        $data = $this->paginate('Retailer');
        $this->set('all', $data);
    }

    public function admin_review_retailers($id = null) {
        $this->set('title_for_layout', 'Retailer\'s Details');
        $this->loadModel('Retailer');
        if (!empty($id)) {
            $data = $this->Retailer->find('first', array('recursive' => 1, 'conditions' => array('Retailer.id' => $id)));
            $this->set('data', $data);
        }
    }

    public function admin_retailers_search() {
        $this->layout = false;
        $this->loadModel('Retailer');
        if (isset($this->data) && !empty($this->data)) {

            $searchData = trim($this->data['mssg']);
            $cond = array();
            if ($this->data['type'] == "new") {
                $cond[] = array('Retailer.status' => 0);
            } elseif ($this->data['type'] == "all") {
                
            }
            if (!empty($searchData)) {
                $cond[] = array(
                    'or' => array(
                        "User.first_name LIKE" => "%" . $searchData . "%",
                        "User.last_name LIKE" => "%" . $searchData . "%",
                        "Retailer.id" => $searchData,
                        "Retailer.business_email LIKE" => "%" . $searchData . "%",
                        "Retailer.business_name LIKE" => "%" . $searchData . "%",
                    /* "Retailer.recreational" =>$searchData,
                      "Retailer.medical" =>$searchData, */
                ));
            }

            $this->paginate = array('recursive' => 1,
                'limit' => 100,
                'conditions' => $cond,
                'order' => array('Retailer.created' => 'DESC'));
            $data = $this->paginate('Retailer');
            $this->set('all', $data);
        }
    }

    public function admin_change_status() {
        $this->autoRender = false;
        $this->loadModel('Retailer');
        $this->loadModel('User');
        if (!empty($this->data)) {
            if (!empty($this->data['id']) && !empty($this->data['type'])) {
                $this->Retailer->unbindModel(array('hasMany' => array('RetailerHour')));
                $data = $this->Retailer->find('first', array('recursive' => 1, 'conditions' => array('Retailer.id' => $this->data['id'])));
                if (!empty($data)) {

                    $url = SITEURL . "login?return_url=" . urlencode(SITEURL . "business-registration/step-2");
                    ;
                    $arr = array('USERNAME' => $data['User']['first_name'], 'BUSINESSNAME' => $data['Retailer']['business_name'], 'STEP2' => $url);
                    if ($this->data['type'] == 1) {
                        $this->Retailer->updateAll(array('Retailer.status' => 1), array('Retailer.id' => $this->data['id']));
                        $this->User->updateAll(array('User.status' => 1), array('User.id' => $data['User']['id']));
                        $this->DATA->AppMail($data['User']['email'], 'AdminBusinessRequestApprove', $arr);
                        $mssg = '<div class="alert alert-success"><button data-dismiss="alert" class="close"></button><strong>Success!</strong> Retailer has been approved. An email will send shortly</div>';
                    } elseif ($this->data['type'] == 3) {
                        $this->Retailer->updateAll(array('Retailer.status' => 3), array('Retailer.id' => $this->data['id']));
                        $this->User->updateAll(array('User.status' => 2), array('User.id' => $data['User']['id']));
                        $this->DATA->AppMail($data['User']['email'], 'AdminBusinessRequestDeclined', $arr);
                        $mssg = '<div class="alert alert-success"><button data-dismiss="alert" class="close"></button><strong>Success!</strong> Retailer has been Declined. An email will send shortly</div>';
                    } else {
                        $mssg = '<div class="alert alert-error"><button data-dismiss="alert" class="close"></button><strong>Error!</strong> we apologize for the inconvenience please try again later.</div>';
                    }
                } else {
                    $mssg = '<div class="alert alert-error"><button data-dismiss="alert" class="close"></button><strong>Error!</strong> we apologize for the inconvenience please try again later.</div>';
                }

                $this->Session->setFlash($mssg, 'default', array('class' => 'ab'), 'msg');
                echo "<script>window.location.reload()</script>";
            }
        }
    }

    public function admin_retailer_menu() {
        $this->set('title_for_layout', ' Retailer Menu');
        $this->loadModel('RetailerMenu');
        $this->paginate = array('recursive' => 1, 'limit' => 100, 'order' => array('RetailerMenu.id' => 'ASC'));
        $data = $this->paginate('RetailerMenu');
        $this->set('retailer_menus', $data);
    }

    public function admin_retailer_menu_search() {
        $this->layout = false;
        $this->loadModel('RetailerMenu');
        if (isset($this->data) && !empty($this->data)) {

            $searchData = trim($this->data['mssg']);
            $cond = array();
            if (!empty($searchData)) {
                $cond[] = array(
                    'or' => array(
                        "RetailerMenu.name LIKE" => "%" . $searchData . "%",
                        "RetailerMenu.id" => $searchData,
                    //"Page.views" => $searchData,
                ));
            }


            $this->paginate = array('recursive' => 0,
                'conditions' => $cond,
                'order' => array('RetailerMenu.id' => 'ASC'),
                'limit' => 100
            );
            $data = $this->paginate('RetailerMenu');
            $this->set('retailer_menus', $data);
        }
    }

    public function admin_retailer_menu_updated_status() {
        $this->autoRender = false;
        if (isset($this->data) && !empty($this->data)) {
            $this->loadModel('RetailerMenu');
            $date = DATE;
            $id = trim($this->data['mssg']);
            $data = $this->RetailerMenu->find('first', array('conditions' => array('RetailerMenu.id' => $id), 'fields' => array('RetailerMenu.id', 'RetailerMenu.status')));
            if (!empty($data)) {
                if ($data['RetailerMenu']['status'] == 1) {
                    $st = 0;
                } elseif ($data['RetailerMenu']['status'] == 0) {
                    $st = 1;
                }

                if ($this->RetailerMenu->updateAll(array('RetailerMenu.status' => $st, 'RetailerMenu.updated' => "'$date'"), array('RetailerMenu.id' => $id))) {
                    if ($st == 0) {
                        echo "Draft";
                    } elseif ($st == 1) {
                        echo "Published";
                    }
                }
            }
        }
    }

    public function admin_retailer_menu_add() {
        $this->set('title_for_layout', ' Retailer Menu');
        $admin_id = $this->Auth->user('id');
        $this->loadModel('RetailerMenu');



        if (!empty($this->request->data)) {
            $this->request->data['RetailerMenu']['user_id'] = $admin_id;
            if ($this->RetailerMenu->save($this->request->data)) {
                $this->Session->setFlash(__d('Labs', 'The Menu has been saved'), 'default', array('class' => 'success'));
                $this->redirect(array('action' => 'retailer_menu'));
            } else {
                $this->Session->setFlash(__d('Labs', 'The Menu could not be saved. Please, try again.'), 'default', array('class' => 'error'));
            }
        }
    }

    public function admin_retailer_menu_edit($id = NUll) {
        $admin_id = $this->Auth->user('id');
        $this->loadModel('RetailerMenu');

        if (!$id && empty($this->request->data)) {
            $this->Session->setFlash(__d('Labs', 'Invalid Menu Id'), 'default', array('class' => 'error'));
            $this->redirect(array('action' => 'retailer_menu'));
        }

        if (!empty($this->request->data)) {
            if ($this->RetailerMenu->save($this->request->data)) {
                $this->Session->setFlash(__d('Labs', 'The Menu has been saved'), 'default', array('class' => 'success'));
                $this->redirect(array('action' => 'retailer_menu'));
            } else {
                $this->Session->setFlash(__d('Labs', 'The Menu could not be saved. Please, try again.'), 'default', array('class' => 'error'));
            }
        }

        if (empty($this->request->data)) {
            $this->request->data = $this->RetailerMenu->findById($id);
        }

        $this->render('admin_retailer_menu_add');
    }

    public function admin_product_list($id = null) {
        $this->layout = '404';
    }

    public function admin_payments() {
        $this->set('title_for_layout', ' Payments');

        $this->loadModel('Payment');
        $this->Payment->bindModel(array('belongsTo' => array('Retailer', 'Subscription')));

        $this->paginate = array('recursive' => 1,
            'conditions' => array(),
            'order' => array('Payment.id' => 'DESC'),
            'limit' => 100
        );
        $data = $this->paginate('Payment');

        $this->set('all', $data);
    }

    public function admin_payments_search() {
        $this->layout = false;
        $this->loadModel('Payment');
        $this->Payment->bindModel(array('belongsTo' => array('Retailer', 'Subscription')), false);

        if (isset($this->data) && !empty($this->data)) {

            $searchData = trim($this->data['mssg']);
            $cond = array();

            if (!empty($searchData)) {
                $cond[] = array(
                    'or' => array(
                        "Payment.id" => $searchData,
                        "Retailer.business_email LIKE" => "%" . $searchData . "%",
                        "Retailer.business_name LIKE" => "%" . $searchData . "%",
                        "Payment.amount" => $searchData,
                        "Subscription.name LIKE" => "%" . $searchData . "%",
                ));
            }

            $this->paginate = array('recursive' => 1,
                'limit' => 100,
                'conditions' => $cond,
                'order' => array('Payment.id' => 'DESC'));
            $data = $this->paginate('Payment');

            $this->set('all', $data);
        }
    }

    public function admin_reservations() {

        $this->set('title_for_layout', 'Reservation');

        $this->loadModel('Reservation');
        $this->Reservation->bindModel(array('belongsTo' => array('User', 'Product')), false);
        $this->Reservation->bindModel(array('belongsTo' => array('Retailer' => array('foreignKey' => false, 'conditions' => array('Retailer.id=Reservation.product_retailer_id')))), false);

        $this->paginate = array('recursive' => 1,
            'conditions' => array(),
            'order' => array('Reservation.id' => 'DESC'),
            'limit' => 100);
        $data = $this->paginate('Reservation');
        $this->set('all', $data);
    }

    public function admin_reservations_search() {
        $this->layout = false;
        $this->loadModel('Reservation');
        $this->Reservation->bindModel(array('belongsTo' => array('User', 'Product')), false);
        $this->Reservation->bindModel(array('belongsTo' => array('Retailer' => array('foreignKey' => false, 'conditions' => array('Retailer.id=Reservation.product_retailer_id')))), false);

        if (isset($this->data) && !empty($this->data)) {
            $searchData = trim($this->data['mssg']);
            $cond = array();

            if (!empty($searchData)) {
                $cond[] = array(
                    'or' => array(
                        "Reservation.id" => $searchData,
                        "Reservation.pick_up_code" => $searchData,
                        "Retailer.business_name LIKE" => "%" . $searchData . "%",
                        "Product.name LIKE" => "%" . $searchData . "%",
                        "Reservation.qty" => $searchData,
                        "User.first_name LIKE" => "%" . $searchData . "%",
                ));
            }

            $this->paginate = array('recursive' => 1,
                'limit' => 100,
                'conditions' => $cond,
                'order' => array('Reservation.id' => 'DESC'));
            $data = $this->paginate('Reservation');
            $this->set('all', $data);
        }
    }

    public function admin_reservtion_cancel($id = null) {
        $this->autoRender = false;
        $this->loadModel('Reservation');
        $this->Reservation->bindModel(array('belongsTo' => array('User', 'Product')), false);
        $this->Reservation->bindModel(array('belongsTo' => array('Retailer' => array('foreignKey' => false, 'conditions' => array('Retailer.id=Reservation.product_retailer_id')))), false);
        if (!empty($id)) {
            $data = $this->Reservation->find('first', array('recursive' => 1, 'conditions' => array('Reservation.id' => $id, 'Reservation.status' => array(0, 1, 2)),
                'fields' => array('Reservation.id', 'Reservation.qty', 'User.id', 'User.email', 'User.first_name', 'Product.name', 'Retailer.id', 'Retailer.business_name', 'Retailer.user_id')));
            if (!empty($data)) {

                $date = DATE;
                $retailer_user_id = $data['Retailer']['user_id'];
                $retailer_data = $this->User->find('first', array('recursive' => -1, 'conditions' => array('User.id' => $retailer_user_id), 'fields' => array('User.email', 'User.first_name')));

                if ($this->Reservation->updateAll(array('Reservation.status' => 5, 'Reservation.updated' => "'$date'"), array('Reservation.id' => $id))) {
                    $arr = array('USERNAME' => $data['User']['first_name'], 'BUSINESSNAME' => $data['Retailer']['business_name'], 'PRODUCTNAME' => $data['Product']['name'], 'PRODUCTQTY' => $data['Reservation']['qty']);
                    $arr_buz = array('USERNAME' => $retailer_data['User']['first_name'], 'BUSINESSNAME' => $data['Retailer']['business_name'], 'PRODUCTNAME' => $data['Product']['name'], 'PRODUCTQTY' => $data['Reservation']['qty']);

                    $this->DATA->AppMail($data['User']['email'], 'ReservationCencelByAdmin', $arr);
                    $this->DATA->AppMail($retailer_data['User']['email'], 'ReservationCencelByAdmin', $arr_buz);

                    $this->Session->setFlash('Reservation has been cancelled ', 'default', array('class' => 'message success'), 'msg');
                } else {
                    $this->Session->setFlash('not updated please try again later ', 'default', array('class' => 'message error'), 'msg');
                }
                $this->redirect($this->referer());
            }
        }
    }

    public function admin_all_product() {
        $this->set('title_for_layout', 'Product Lising');

        $this->loadModel('Product');
        $this->Product->bindModel(array('belongsTo' => array('User', 'Retailer')));
        $this->Product->bindModel(array('hasOne' => array('RetailerMenu' => array(
                    'foreignKey' => false,
                    'conditions' => array('RetailerMenu.id=Product.retailer_menu_id')
                )
            )
                )
        );
        $this->paginate = array('recursive' => 1,
            'conditions' => array(),
            'order' => array('Product.id' => 'DESC'),
            'limit' => 100);
        $data = $this->paginate('Product');
        $this->set('all_product', $data);
    }

    public function admin_product_search() {
        $this->layout = false;
        if (isset($this->data) && !empty($this->data)) {
            $searchData = trim($this->data['mssg']);
            $cond = array();
            $this->loadModel('Product');
            $this->Product->bindModel(array('belongsTo' => array('User', 'Retailer')));
            $this->Product->bindModel(array('hasOne' => array('RetailerMenu' => array(
                        'foreignKey' => false,
                        'conditions' => array('RetailerMenu.id=Product.retailer_menu_id')
                    )
                )
                    )
            );
            if (!empty($searchData)) {
                $cond[] = array(
                    'or' => array(
                        "Product.Name LIKE" => "%" . $searchData . "%",
                        "Product.description LIKE" => "%" . $searchData . "%",
                        "Product.id" => $searchData,
                ));
            }

            $this->paginate = array('recursive' => 1,
                'conditions' => $cond,
                'order' => array('Product.id' => 'DESC'),
                'limit' => 100);
            $data = $this->paginate('Product');
            $this->set('all_product', $data);
        }
    }

    public function admin_product_update() {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            if (isset($this->data) && !empty($this->data)) {
                $id = trim($this->data['Pid']);
                $ty = $this->data['type'];
                $this->loadModel('Product');
                if ($ty == 1) {
                    $this->Product->updateAll(array('Product.status' => 1), array('Product.id' => $id));
                    $ab = '<a onclick="change_status(' . $id . ',0)" href="javascript:void(0);"><i class="icon-remove"></i> <span> Deactivate</span> </a>';
                    echo "<script>$('#st_" . $id . "').html('Active'); $('#sp_" . $id . "').html('" . $ab . "');  </script>";
                } elseif ($ty == 0) {
                    $this->Product->updateAll(array('Product.status' => 0), array('Product.id' => $id));
                    $ab = '<a onclick="change_status(' . $id . ',1)" href="javascript:void(0);"><i class="icon-remove"></i> <span> Active</span> </a>';
                    echo "<script>$('#st_" . $id . "').html('Deactivate By Admin'); $('#sp_" . $id . "').html('" . $ab . "');  </script>";
                }
            }
        }
    }

    public function admin_edit_product($id = null) {
        $this->set('title_for_layout', 'Edit Product');
        $this->loadModel('Product');
        $product_data = $this->Product->findById($id);
        if (isset($this->request->data) && !empty($this->request->data)) {


// check image isset or not if not then remove image validation 
            if (isset($this->data['Product']['product_image']) && $this->data['Product']['product_image']['error'] == 4) {
                $this->Product->validator()->remove('product_image');
            }
            $this->Product->set($this->request->data);
            $r1 = $this->Product->validates();
            if ($r1) {
                if ($this->data['Product']['product_image']['error'] == 0 && !empty($this->data['Product']['product_image']['name'])) {
                    $id = "product_image_" . rand(1000, 999999);
                    $rev = strrev($this->data['Product']['product_image']['name']);
                    $file_ext = explode(".", $rev);
                    $new_file = strtolower(strrev($file_ext[0]));
                    $product_image_name = $id . "." . $new_file;
                    $product_image_path = WWW_ROOT . 'data/product_image/' . $product_image_name;

                    if (move_uploaded_file($this->data['Product']['product_image']['tmp_name'], $product_image_path)) {
                        if (file_exists($product_image_path)) {
                            $old_product_image = WWW_ROOT . 'data/product_image/' . $product_data['Product']['product_image'];
                            unlink($old_product_image);
                            $this->request->data['Product']['product_image'] = $product_image_name;
                        }
                    }
                } else {
                    $this->request->data['Product']['product_image'] = $product_data['Product']['product_image'];
                }
                if ($this->Product->save($this->request->data['Product'])) {
                    $this->Session->setFlash('Product info has been update successfully', 'default', array('class' => 'message success'), 'msg');
                    $this->redirect(array('action' => 'all_product', 'admin' => true));
                }
            } else {
                $this->request->data = $product_data;
            }
        } else {
            if (!empty($id)) {
                if (empty($product_data)) {
                    $this->layout = '404';
                } else {
                    $this->request->data = $product_data;
// get menu list golbal and this user 
                    $this->loadModel('RetailerMenu');
                    $menu_con_or['RetailerMenu.global'] = true;
                    $menu_con_or['RetailerMenu.user_id'] = $product_data['Product']['user_id'];
                    $menu_options = $this->RetailerMenu->find('list', array('conditions' => array(
                            'or' => $menu_con_or
                        )
                            )
                    );
                    $this->set('menu_options', $menu_options);
                }
            }
        }
    }

    public function admin_view_product($id = null) {
        $this->set('title_for_layout', 'View Product');
        $this->loadModel('Product');
        $this->Product->bindModel(array('belongsTo' => array('User')));
        $this->Product->bindModel(array('belongsTo' => array('Retailer')));
        $this->Product->bindModel(array('belongsTo' => array('Retailer' => array(
                    'foreignKey' => false,
                    'conditions' => array('Retailer.id=Product.retailer_id')
                )
            )
                )
        );
        $product_data = $this->Product->findById($id);
        if (!empty($id)) {
            if (empty($product_data)) {
                $this->layout = '404';
            } else {
                $this->set('data', $product_data);
            }
        }
    }

// advertise management
    public function admin_all_ads() {
        $this->set('title_for_layout', 'Advertise  Lising');

        $this->loadModel('Ad');
        $this->paginate = array(
            'conditions' => array(),
            'order' => array('Ad.id' => 'DESC'),
            'limit' => 100);
        $Ad_data = $this->paginate('Ad');
        $this->set('all_ads', $Ad_data);
    }

    public function admin_ads_search() {
        $this->layout = false;
        if (isset($this->data) && !empty($this->data)) {
            $searchData = trim($this->data['mssg']);
            $cond = array();
            $this->loadModel('Ad');
            if (!empty($searchData)) {
                $cond[] = array(
                    'or' => array(
                        "Ad.title LIKE" => "%" . $searchData . "%",
                        "Ad.description LIKE" => "%" . $searchData . "%",
                        "Ad.id" => $searchData,
                ));
            }

            $this->paginate = array('recursive' => -1,
                'conditions' => $cond,
                'order' => array('Ad.id' => 'DESC'),
                'limit' => 100);
            $Ad_data = $this->paginate('Ad');
            $this->set('all_ads', $Ad_data);
        }
    }

    public function admin_ads_update() {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            if (isset($this->data) && !empty($this->data)) {
                $id = trim($this->data['Aid']);
                $ty = $this->data['type'];
                $this->loadModel('Ad');
                if ($ty == 1) {
                    $this->Ad->updateAll(array('Ad.status' => 1), array('Ad.id' => $id));
                    $ab = '<a onclick="change_status(' . $id . ',0)" href="javascript:void(0);"><i class="icon-remove"></i> <span> Deactivate</span> </a>';
                    echo "<script>$('#st_" . $id . "').html('Active'); $('#sp_" . $id . "').html('" . $ab . "');  </script>";
                } elseif ($ty == 0) {
                    $this->Ad->updateAll(array('Ad.status' => 0), array('Ad.id' => $id));
                    $ab = '<a onclick="change_status(' . $id . ',1)" href="javascript:void(0);"><i class="icon-remove"></i> <span> Active</span> </a>';
                    echo "<script>$('#st_" . $id . "').html('Deactivate'); $('#sp_" . $id . "').html('" . $ab . "');  </script>";
                }
            }
        }
    }

    public function admin_edit_ads($id = null) {
        $this->set('title_for_layout', 'Edit Advertise');
        $this->loadModel('Ad');
        $advertise_data = $this->Ad->findById($id);
        if (isset($this->request->data) && !empty($this->request->data)) {
            if ($this->data['Ad']['image']['error'] == 0 && !empty($this->data['Ad']['image']['name'])) {
                
            } else {
                $this->Ad->validator()->remove('image');
            }
            $this->Ad->set($this->request->data);
            $r1 = $this->Ad->validates();
            if ($r1) {
                if ($this->data['Ad']['image']['error'] == 0 && !empty($this->data['Ad']['image']['name'])) {
                    $id = "advertise_image_" . rand(1000, 999999);
                    $rev = strrev($this->data['Ad']['image']['name']);
                    $file_ext = explode(".", $rev);
                    $new_file = strtolower(strrev($file_ext[0]));
                    $advertise_image_name = $id . "." . $new_file;
                    $advertise_image_path = WWW_ROOT . 'data/advertise_image/' . $advertise_image_name;

                    if (move_uploaded_file($this->data['Ad']['image']['tmp_name'], $advertise_image_path)) {
                        $ImgAtt = getimagesize($advertise_image_path);
                        $seclect_size = explode("x", $this->data['Ad']['size']);
                        if (($ImgAtt[0] > $seclect_size[0] + Advertise_Plus_Minus || $ImgAtt[0] < $seclect_size[0] - Advertise_Plus_Minus ) && ($ImgAtt[1] > $seclect_size[1] + Advertise_Plus_Minus || $ImgAtt[1] < $seclect_size[1] - Advertise_Plus_Minus)) {
                            unlink($advertise_image_path);
                            $this->Session->setFlash(__('Please upload an image accroding to size, Your uploaded image size is ' . $ImgAtt[0] . "x" . $ImgAtt[1]), 'default', array('class' => 'message error'), 'msg');
                            $this->request->data = $advertise_data;
                        } else {
                            if (file_exists($advertise_image_path)) {
                                $old_ad_image = WWW_ROOT . 'data/advertise_image/' . $advertise_data['Ad']['image'];
                                unlink($old_ad_image);
                                $this->request->data['Ad']['image'] = $advertise_image_name;
                            }
                            if ($this->Ad->save($this->request->data['Ad'])) {
                                $this->Session->setFlash('Advertise info has been update successfully', 'default', array('class' => 'message success'), 'msg');
                                $this->redirect(array('action' => 'all_ads', 'admin' => true));
                            }
                        }
                    }
                } else {
                    $this->request->data['Ad']['image'] = $advertise_data['Ad']['image'];
                    if ($this->Ad->save($this->request->data['Ad'])) {
                        $this->Session->setFlash('Advertise info has been update successfully', 'default', array('class' => 'message success'), 'msg');
                        $this->redirect(array('action' => 'all_ads', 'admin' => true));
                    }
                }
            } else {
                $this->request->data = $advertise_data;
            }
        } else {
            if (!empty($id)) {
                if (empty($advertise_data)) {
                    $this->layout = '404';
                } else {
                    $this->request->data = $advertise_data;
                }
            }
        }
    }

    public function admin_create_new_ad() {
        $this->set('title_for_layout', 'Add Advertise');
        if (isset($this->request->data) && !empty($this->request->data)) {
            $this->loadModel('Ad');
            $this->Ad->set($this->request->data);
            $r1 = $this->Ad->validates();
            if ($r1) {
                if ($this->data['Ad']['image']['error'] == 0 && !empty($this->data['Ad']['image']['name'])) {
                    $id = "advertise_image_" . rand(1000, 999999);
                    $name = ($this->data['Ad']['image']['name']);
                    $file_ext = @end(explode(".", $name));
                    $advertise_image_name = $id . "." . $file_ext;
                    $advertise_image_path = WWW_ROOT . 'data/advertise_image/' . $advertise_image_name;
                    if (move_uploaded_file($this->data['Ad']['image']['tmp_name'], $advertise_image_path)) {
                        $ImgAtt = getimagesize($advertise_image_path);
                        $seclect_size = explode("x", $this->data['Ad']['size']);
                        if (($ImgAtt[0] > $seclect_size[0] + Advertise_Plus_Minus || $ImgAtt[0] < $seclect_size[0] - Advertise_Plus_Minus ) && ($ImgAtt[1] > $seclect_size[1] + Advertise_Plus_Minus || $ImgAtt[1] < $seclect_size[1] - Advertise_Plus_Minus)) {
                            unlink($advertise_image_path);
                            $this->Session->setFlash(__('Please upload an image accroding to size, Your uploaded image size is ' . $ImgAtt[0] . "x" . $ImgAtt[1]), 'default', array('class' => 'message error'), 'msg');
                        } else {
                            $this->request->data['Ad']['image'] = $advertise_image_name;
                            if ($this->Ad->save($this->request->data['Ad'])) {
                                $this->Session->setFlash('Advertise info has been saved successfully', 'default', array('class' => 'message success'), 'msg');
                                $this->redirect(array('action' => 'all_ads', 'admin' => true));
                            } else {
                                unlink($advertise_image_path);
                                $this->Session->setFlash(__('Sorry,Please try again after some time'), 'default', array('class' => 'message error'), 'msg');
                            }
                        }
                    }
                } else {
                    $this->Session->setFlash(__('Sorry,Please try again after some time'), 'default', array('class' => 'message error'), 'msg');
                }
            }
        }
    }

// retailer availablity section listing function
    public function admin_retailer_availability() {
        $this->set('title_for_layout', 'Retailer Availability');
        $this->loadModel('AvailabilityRecord');
        $this->paginate = array(
            'conditions' => array(),
            'order' => array('AvailabilityRecord.id' => 'ASC'),
            'limit' => 100);
        $availability_record = $this->paginate('AvailabilityRecord');
        $this->set('all_availability_record', $availability_record);
    }

// retailer availablity record update
    public function admin_retailer_availability_update() {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            if (isset($this->data) && !empty($this->data)) {
                $this->loadModel('AvailabilityRecord');
                $id = trim($this->data['R_A_id']);
                $ty = $this->data['type'];
                if ($ty == 1) {
                    $this->AvailabilityRecord->updateAll(array('AvailabilityRecord.status' => 1), array('AvailabilityRecord.id' => $id));
                    $ab = '<a onclick="change_status(' . $id . ',0)" href="javascript:void(0);"><i class="icon-remove"></i> <span> Deactivate</span> </a>';
                    echo "<script>$('#st_" . $id . "').html('Active'); $('#sp_" . $id . "').html('" . $ab . "');  </script>";
                } elseif ($ty == 0) {
                    $this->AvailabilityRecord->updateAll(array('AvailabilityRecord.status' => 0), array('AvailabilityRecord.id' => $id));
                    $ab = '<a onclick="change_status(' . $id . ',1)" href="javascript:void(0);"><i class="icon-remove"></i> <span> Active</span> </a>';
                    echo "<script>$('#st_" . $id . "').html('Deactivate'); $('#sp_" . $id . "').html('" . $ab . "');  </script>";
                }
            }
        }
    }

// search retailer availablity 
    public function admin_retailer_availability_search() {
        $this->layout = false;
        if (isset($this->data) && !empty($this->data)) {
            $searchData = trim($this->data['mssg']);
            $cond = array();
            $this->loadModel('AvailabilityRecord');
            if (!empty($searchData)) {
                $cond[] = array(
                    'or' => array(
                        "AvailabilityRecord.name LIKE" => "%" . $searchData . "%",
                        "AvailabilityRecord.id" => $searchData,
                ));
            }

            $this->paginate = array('recursive' => -1,
                'conditions' => $cond,
                'order' => array('AvailabilityRecord.id' => 'DESC'),
                'limit' => 100);
            $availability_record = $this->paginate('AvailabilityRecord');
            $this->set('all_availability_record', $availability_record);
        }
    }

// add retailer availablity section 
    public function admin_create_retailer_availability() {
        $this->set('title_for_layout', 'Add Retailer Availability');
        if (isset($this->request->data) && !empty($this->request->data)) {
            $this->loadModel('AvailabilityRecord');
            $this->AvailabilityRecord->set($this->request->data);
            $r1 = $this->AvailabilityRecord->validates();
            if ($r1) {
                if ($this->data['AvailabilityRecord']['image']['error'] == 0 && !empty($this->data['AvailabilityRecord']['image']['name'])) {
                    $id = "availability_image_" . rand(1000, 999999);
                    $name = ($this->data['AvailabilityRecord']['image']['name']);
                    $file_ext = @end(explode(".", $name));
                    $advertise_image_name = $id . "." . $file_ext;
                    $advertise_image_path = WWW_ROOT . 'data/availability_image/' . $advertise_image_name;
                    if (move_uploaded_file($this->data['AvailabilityRecord']['image']['tmp_name'], $advertise_image_path)) {
                        $ImgAtt = getimagesize($advertise_image_path);
                        $seclect_size = explode("x", RETAILER_AVAILABLITY_ICON_SIZE);
                        if (($ImgAtt[0] > $seclect_size[0] + RETAILER_AVAILABLITY_ICON_Plus_Minus || $ImgAtt[0] < $seclect_size[0] - RETAILER_AVAILABLITY_ICON_Plus_Minus ) && ($ImgAtt[1] > $seclect_size[1] + RETAILER_AVAILABLITY_ICON_Plus_Minus || $ImgAtt[1] < $seclect_size[1] - RETAILER_AVAILABLITY_ICON_Plus_Minus)) {
                            unlink($advertise_image_path);
                            $this->Session->setFlash(__('Please upload an image' . RETAILER_AVAILABLITY_ICON_SIZE . ' ! Your uploaded image size is ' . $ImgAtt[0] . "x" . $ImgAtt[1]), 'default', array('class' => 'message error'), 'msg');
                        } else {
                            $this->request->data['AvailabilityRecord']['image'] = $advertise_image_name;
                            $this->request->data['AvailabilityRecord']['alias'] = $this->AvailabilityRecord->createSlug($this->request->data['AvailabilityRecord']['name'], 10);
                            if ($this->AvailabilityRecord->save($this->request->data['AvailabilityRecord'])) {
                                $this->Session->setFlash('Advertise info has been saved successfully', 'default', array('class' => 'message success'), 'msg');
                                $this->redirect(array('action' => 'retailer_availability', 'admin' => true));
                            } else {
                                unlink($advertise_image_path);
                                $this->Session->setFlash(__('Sorry, Please try again after some time'), 'default', array('class' => 'message error'), 'msg');
                            }
                        }
                    }
                } else {
                    $this->Session->setFlash(__('Sorry, Please try again after some time'), 'default', array('class' => 'message error'), 'msg');
                }
            }
        }
    }

// edit retailer availablity section 
    public function admin_edit_retailer_availability($availablity_id = null) {
        $this->set('title_for_layout', 'Edit Retailer Availability');
        if ($availablity_id) {
            $this->loadModel('AvailabilityRecord');
            $availablity_data = $this->AvailabilityRecord->findById($availablity_id);
            if ($this->request->is('post') || $this->request->is('put')) {
                if (isset($this->request->data) && !empty($this->request->data)) {
                    $this->loadModel('AvailabilityRecord');
                    $this->AvailabilityRecord->set($this->request->data);
                    $r1 = $this->AvailabilityRecord->validates();
                    if ($r1) {
                        if ($this->data['AvailabilityRecord']['image']['error'] == 0 && !empty($this->data['AvailabilityRecord']['image']['name'])) {
                            $id = "availability_image_" . rand(1000, 999999);
                            $name = ($this->data['AvailabilityRecord']['image']['name']);
                            $file_ext = @end(explode(".", $name));
                            $advertise_image_name = $id . "." . $file_ext;
                            $advertise_image_path = WWW_ROOT . 'data/availability_image/' . $advertise_image_name;
                            if (move_uploaded_file($this->data['AvailabilityRecord']['image']['tmp_name'], $advertise_image_path)) {
                                $ImgAtt = getimagesize($advertise_image_path);
                                $seclect_size = explode("x", RETAILER_AVAILABLITY_ICON_SIZE);
                                if (($ImgAtt[0] > $seclect_size[0] + RETAILER_AVAILABLITY_ICON_Plus_Minus || $ImgAtt[0] < $seclect_size[0] - RETAILER_AVAILABLITY_ICON_Plus_Minus ) && ($ImgAtt[1] > $seclect_size[1] + RETAILER_AVAILABLITY_ICON_Plus_Minus || $ImgAtt[1] < $seclect_size[1] - RETAILER_AVAILABLITY_ICON_Plus_Minus)) {
                                    unlink($advertise_image_path);
                                    $this->Session->setFlash(__('Please upload an image ' . RETAILER_AVAILABLITY_ICON_SIZE . ' ! Your uploaded image size is ' . $ImgAtt[0] . "x" . $ImgAtt[1]), 'default', array('class' => 'message error'), 'msg');
                                    $this->redirect(array('action' => 'edit_retailer_availability', $availablity_id, 'admin' => true));
                                } else {
                                    $old_availblity_image = WWW_ROOT . 'data/availability_image/' . $availablity_data['AvailabilityRecord']['image'];
                                    if ($availablity_data['AvailabilityRecord']['image'] != null && file_exists($old_availblity_image)) {
                                        unlink($old_availblity_image);
                                    }
                                    $availability_image = $advertise_image_name;
                                }
                            }
                        } else {
                            $availability_image = $availablity_data['AvailabilityRecord']['image'];
                        }
                        $this->request->data['AvailabilityRecord']['image'] = $availability_image;
                        $this->request->data['AvailabilityRecord']['id'] = $availablity_id;
                        if ($this->AvailabilityRecord->save($this->request->data)) {
                            $this->Session->setFlash(__('Availability data updated successfully'), 'default', array('class' => 'message success'), 'msg');
                            $this->redirect(array('action' => 'retailer_availability', 'admin' => true));
                        } else {
                            $this->Session->setFlash(__('Sorry, Please try again after some time'), 'default', array('class' => 'message error'), 'msg');
                        }
                    }
                }
            } else {
                $this->request->data = $availablity_data;
            }
        }
    }

// deal management 
    public function admin_all_deal() {
        $this->set('title_for_layout', 'Deal Lising');

        $this->loadModel('ProductDeal');
        $this->loadModel('Product');

        $this->ProductDeal->bindModel(array('belongsTo' => array('Product')));
        $this->Product->bindModel(array('belongsTo' => array('User')));
        $this->Product->bindModel(array('hasOne' => array('RetailerMenu' => array(
                    'foreignKey' => false,
                    'conditions' => array('RetailerMenu.id=Product.retailer_menu_id')
                )
            )
                )
        );
        $this->paginate = array('recursive' => 2,
            'conditions' => array(),
            'order' => array('ProductDeal.id' => 'ASC'),
            'limit' => 100);
        $data = $this->paginate('ProductDeal');
        $this->set('all_deal', $data);
    }

// deal active or deactive
    public function admin_deal_update() {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            if (isset($this->data) && !empty($this->data)) {
                $this->loadModel('ProductDeal');
                $id = trim($this->data['D_id']);
                $ty = $this->data['type'];
                if ($ty == 1) {
                    $this->ProductDeal->updateAll(array('ProductDeal.status' => 1), array('ProductDeal.id' => $id));
                    $ab = '<a onclick="change_status(' . $id . ',0)" href="javascript:void(0);"><i class="icon-remove"></i> <span> Deactivate</span> </a>';
                    echo "<script>$('#st_" . $id . "').html('Active'); $('#sp_" . $id . "').html('" . $ab . "');  </script>";
                } elseif ($ty == 0) {
                    $this->ProductDeal->updateAll(array('ProductDeal.status' => 2), array('ProductDeal.id' => $id));
                    $ab = '<a onclick="change_status(' . $id . ',1)" href="javascript:void(0);"><i class="icon-remove"></i> <span> Active</span> </a>';
                    echo "<script>$('#st_" . $id . "').html('Deactive by Admin'); $('#sp_" . $id . "').html('" . $ab . "');  </script>";
                }
            }
        }
    }

// search deal section
    public function admin_deal_search() {
        $this->layout = false;
        if (isset($this->data) && !empty($this->data)) {
            $searchData = trim($this->data['mssg']);
            $cond = array();
            if (!empty($searchData)) {
                $cond[] = array(
                    'or' => array(
                        "ProductDeal.title LIKE" => "%" . $searchData . "%",
                        "ProductDeal.description LIKE" => "%" . $searchData . "%",
                        "ProductDeal.id" => $searchData,
                ));
            }

            $this->loadModel('ProductDeal');
            $this->loadModel('Product');

            $this->ProductDeal->bindModel(array('belongsTo' => array('Product')));
            $this->Product->bindModel(array('belongsTo' => array('User')));
            $this->Product->bindModel(array('hasOne' => array('RetailerMenu' => array(
                        'foreignKey' => false,
                        'conditions' => array('RetailerMenu.id=Product.retailer_menu_id')
                    )
                )
                    )
            );
            $this->paginate = array('recursive' => 2,
                'conditions' => $cond,
                'order' => array('ProductDeal.id' => 'DESC'),
                'limit' => 100);
            $data = $this->paginate('ProductDeal');
            $this->set('all_deal', $data);
        }
    }

// Edit deal
    public function admin_edit_deal($deal_id = null) {
        if ($deal_id) {
            $this->loadModel('ProductDeal');
            $deal_data = $this->ProductDeal->findById($deal_id);
            if ($this->request->is('post') || $this->request->is('put')) {
                if (isset($this->data) && !empty($this->data)) {
                    $this->ProductDeal->set($this->request->data);
                    $r1 = $this->ProductDeal->validates();
                    if ($r1) {
                        if ($this->data['ProductDeal']['deal_image']['error'] == 0 && !empty($this->data['ProductDeal']['deal_image']['name'])) {
                            $id = "deal_image_" . rand(1000, 999999);
                            $name = ($this->data['ProductDeal']['deal_image']['name']);
                            $file_ext = @end(explode(".", $name));
                            $deal_image_name = $id . "." . $file_ext;
                            $deal_image_path = WWW_ROOT . 'data/deal_image/' . $deal_image_name;
                            if (move_uploaded_file($this->data['ProductDeal']['deal_image']['tmp_name'], $deal_image_path)) {
                                $old_deal_image = WWW_ROOT . 'data/deal_image/' . $deal_data['ProductDeal']['deal_image'];

                                if ($deal_data['ProductDeal']['deal_image'] != null && file_exists($old_deal_image)) {
                                    unlink($old_deal_image);
                                }
                                $deal_image = $deal_image_name;
                            }
                        } else {
                            $deal_image = $deal_data['ProductDeal']['deal_image'];
                        }
                        $this->request->data['ProductDeal']['deal_image'] = $deal_image;
                        $this->request->data['ProductDeal']['id'] = $deal_id;
                        if ($this->ProductDeal->save($this->request->data['ProductDeal'])) {
                            $this->Session->setFlash(__('Deal successgully update'), 'default', array('class' => 'message success'), 'msg');
                            $this->redirect(array('action' => 'all_deal', 'admin' => true));
                        } else {
                            $this->Session->setFlash(__('Sorry, Please try again after some time'), 'default', array('class' => 'message error'), 'msg');
                        }
                    } else {
                        $this->set('deal_data', $deal_data);
                    }
                }
            } else {
                $this->request->data = $deal_data;
            }
        } else {
            $this->layout = '404';
        }
    }

// footer page category
    public function admin_footer_cat() {
        $this->set('title_for_layout', 'All Footer Category');
        $this->loadModel('FooterCategory');
        $this->paginate = array(
            'limit' => 100,
            'conditions' => array(),
            'order' => array('FooterCategory.id' => 'ASC'));
        $footer_all_cat = $this->paginate('FooterCategory');
        $this->set('footer_all_cat', $footer_all_cat);
    }

// footer page category
    public function admin_edit_footer_cat($cat_id = null) {
        if ($cat_id) {
            $this->set('title_for_layout', 'All Footer Category');
            $this->loadModel('FooterCategory');
            $footer_cate_data = $this->FooterCategory->find('first', array('conditions' => array('FooterCategory.id' => $cat_id)));
            if ($this->request->is('post') || $this->request->is('put')) {
                if (isset($this->request->data) && !empty($this->request->data)) {

                    if ($this->request->data['FooterCategory']['category_name'] == $footer_cate_data['FooterCategory']['category_name']) {
                        $this->FooterCategory->validator()->remove('category_name');
                    }

                    $this->FooterCategory->set($this->request->data);
                    $r1 = $this->FooterCategory->validates();
                    if ($r1) {
                        $this->request->data['FooterCategory']['id'] = $footer_cate_data['FooterCategory']['id'];
                        if ($this->FooterCategory->save($this->request->data)) {
                            $this->Session->setFlash(__('Footer category Edited successfully'), 'default', array('class' => 'message success'), 'msg');
                            $this->redirect(array('action' => 'admin_footer_cat', 'admin' => true));
                        } else {
                            $this->Session->setFlash(__('Please try again .Something went wrong'), 'default', array('class' => 'message success'), 'msg');
                        }
                    }
                }
            } else {
                $this->request->data = $footer_cate_data;
            }
        } else {
            
        }
    }

// deal active or deactive
    public function admin_update_footer_cat() {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            if (isset($this->data) && !empty($this->data)) {
                $this->loadModel('FooterCategory');
                $id = trim($this->data['F_id']);
                $ty = $this->data['type'];
                if ($ty == 1) {
                    $this->FooterCategory->updateAll(array('FooterCategory.status' => 1), array('FooterCategory.id' => $id));
                    $ab = '<a onclick="change_status(' . $id . ',0)" href="javascript:void(0);"><i class="icon-remove"></i> <span> Deactivate</span> </a>';
                    echo "<script>$('#st_" . $id . "').html('Active'); $('#sp_" . $id . "').html('" . $ab . "');  </script>";
                } elseif ($ty == 0) {
                    $this->FooterCategory->updateAll(array('FooterCategory.status' => 0), array('FooterCategory.id' => $id));
                    $ab = '<a onclick="change_status(' . $id . ',1)" href="javascript:void(0);"><i class="icon-remove"></i> <span> Active</span> </a>';
                    echo "<script>$('#st_" . $id . "').html('Deactive'); $('#sp_" . $id . "').html('" . $ab . "');  </script>";
                }
            }
        }
    }

// deal active or deactive
    public function admin_update_footer_link() {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            if (isset($this->data) && !empty($this->data)) {
                $this->loadModel('FooterLink');
                $id = trim($this->data['L_id']);
                $ty = $this->data['type'];
                if ($ty == 1) {
                    $this->FooterLink->updateAll(array('FooterLink.status' => 1), array('FooterLink.id' => $id));
                    $ab = '<a onclick="change_status(' . $id . ',0)" href="javascript:void(0);"><i class="icon-remove"></i> <span> Deactivate</span> </a>';
                    echo "<script>$('#st_" . $id . "').html('Active'); $('#sp_" . $id . "').html('" . $ab . "');  </script>";
                } elseif ($ty == 0) {
                    $this->FooterLink->updateAll(array('FooterLink.status' => 0), array('FooterLink.id' => $id));
                    $ab = '<a onclick="change_status(' . $id . ',1)" href="javascript:void(0);"><i class="icon-remove"></i> <span> Active</span> </a>';
                    echo "<script>$('#st_" . $id . "').html('Deactive'); $('#sp_" . $id . "').html('" . $ab . "');  </script>";
                }
            }
        }
    }

// deal active or deactive
    public function admin_add_footer_link($cate_id = null) {
        if ($cate_id) {
            $this->loadModel('Page');
            $all_page = $this->Page->find('list', array('conditions' => array(), 'fields' => array('url', 'title')));
            $this->set('all_page', $all_page);
            $this->loadModel('FooterLink');
            $total_footer_link_count = $this->FooterLink->find('count', array('conditions' => array('FooterLink.footer_category_id' => $cate_id)));
            $this->set('total_footer_link_count', $total_footer_link_count + 1);

            $this->loadModel('FooterLink');
            $this->FooterLink->bindModel(array('belongsTo' => array('FooterCategory' => array(
                        'foreignKey' => false,
                        'conditions' => array('FooterCategory.id=FooterLink.footer_category_id')
                    )
                )
                    )
            );
            $this->paginate = array(
                'limit' => 100,
                'conditions' => array('FooterLink.footer_category_id' => $cate_id),
                'order' => array('FooterLink.id' => 'ASC'));
            $footer_all_page = $this->paginate('FooterLink');
            $this->set('footer_all_page', $footer_all_page);



            if (isset($this->data) && !empty($this->data)) {
                $this->loadModel('FooterLink');
                $this->request->data['FooterLink']['footer_category_id'] = $cate_id;
                $this->request->data['FooterLink']['status'] = 1;



                $this->FooterLink->set($this->request->data);
                $r1 = $this->FooterLink->validates();
                if ($r1) {

                    if ($this->FooterLink->save($this->request->data['FooterLink'])) {
// swap all position of based on this link
// swap 

                        if (isset($this->request->data['FooterLink']['total_current_child']) && $this->request->data['FooterLink']['total_current_child'] != $this->request->data['FooterLink']['position']) {
                            $last_footer_id = $this->FooterLink->getLastInsertId();
                            $all_footer_link = $this->FooterLink->find('all', array('conditions' => array('FooterLink.id <>' => $last_footer_id, 'FooterLink.footer_category_id' => $cate_id, 'FooterLink.position >=' => $this->request->data['FooterLink']['position'])));
                            if (isset($all_footer_link) && !empty($all_footer_link)) {
                                foreach ($all_footer_link as $key => $footer_link) {
                                    $datas[$key]['position'] = $footer_link['FooterLink']['position'] + 1;
                                    $datas[$key]['id'] = $footer_link['FooterLink']['id'];
                                }
                                $this->FooterLink->saveMany($datas, array('deep' => true));
                            }
                        } else {
                            
                        }
                        $this->Session->setFlash(__('Footer link added successfully'), 'default', array('class' => 'message success'), 'msg');
                        $this->redirect(array('action' => 'admin_add_footer_link', $cate_id, 'admin' => true));
                    } else {
                        $this->Session->setFlash(__('Sorry something went wrong.Please try again'), 'default', array('class' => 'message error'), 'msg');
                        $this->redirect(array('action' => 'admin_add_footer_link', $cate_id, 'admin' => true));
                    }
                }
            }
        }
    }

// deal active or deactive
    public function admin_edit_footer_link($link_id = null) {
        $this->loadModel('FooterLink');
        $footer_link_data = $this->FooterLink->find('first', array('conditions' => array('FooterLink.id' => $link_id)));

        $this->loadModel('Page');
        $all_page = $this->Page->find('list', array('conditions' => array(), 'fields' => array('url', 'title')));
        $this->set('all_page', $all_page);
        $this->loadModel('FooterCategory');
        $all_footer_cat = $this->FooterCategory->find('list', array('conditions' => array(), 'fields' => array('id', 'category_name')));
        $this->set('all_footer_cat', $all_footer_cat);
        $total_footer_link_count = $this->FooterLink->find('count', array('conditions' => array('FooterLink.footer_category_id' => $footer_link_data['FooterLink']['footer_category_id'])));
        $this->set('total_footer_link_count', $total_footer_link_count);


        $this->loadModel('FooterLink');
        $this->FooterLink->bindModel(array('belongsTo' => array('FooterCategory' => array(
                    'foreignKey' => false,
                    'conditions' => array('FooterCategory.id=FooterLink.footer_category_id')
                )
            )
                )
        );
        $this->paginate = array(
            'limit' => 100,
            'conditions' => array('FooterLink.footer_category_id' => $footer_link_data['FooterLink']['footer_category_id']),
            'order' => array('FooterLink.id' => 'ASC'));
        $footer_all_page = $this->paginate('FooterLink');
        $this->set('footer_all_page', $footer_all_page);

        if (isset($this->data) && !empty($this->data)) {
            $this->loadModel('FooterLink');
            $this->request->data['FooterLink']['id'] = $link_id;

            if ($this->request->data['FooterLink']['link_name'] == $footer_link_data['FooterLink']['link_name']) {
                $this->FooterLink->validator()->remove('link_name');
            }

            $this->FooterLink->set($this->request->data);
            $r1 = $this->FooterLink->validates();
            if ($r1) {



                if ($this->FooterLink->save($this->request->data)) {
                    if ($footer_link_data['FooterLink']['footer_category_id'] != $this->request->data['FooterLink']['footer_category_id'] || $footer_link_data['FooterLink']['position'] != $this->request->data['FooterLink']['position']) {

// check current position or new position 
// if current position is greater then old position  then get high record and update
// if new position is less then old position then get lowest record and update
                        $spacial_cond = array();

                        if ($footer_link_data['FooterLink']['footer_category_id'] == $this->request->data['FooterLink']['footer_category_id']) {


                            if ($footer_link_data['FooterLink']['position'] > $this->request->data['FooterLink']['position']) {
                                $spacial_cond = array('FooterLink.position <=' => $footer_link_data['FooterLink']['position'], 'FooterLink.position >=' => $this->request->data['FooterLink']['position']);
                            } else {
                                $spacial_cond = array('FooterLink.position >=' => $footer_link_data['FooterLink']['position'], 'FooterLink.position <=' => $this->request->data['FooterLink']['position']);
                            }
                        } else {
                            $spacial_cond = array('FooterLink.position >=' => $this->request->data['FooterLink']['position']);
                        }

                        $all_footer_link = $this->FooterLink->find('all', array('conditions' => array('FooterLink.id <>' => $footer_link_data['FooterLink']['id'], 'FooterLink.footer_category_id' => $this->request->data['FooterLink']['footer_category_id'], $spacial_cond)));
                        if (isset($all_footer_link) && !empty($all_footer_link)) {
                            foreach ($all_footer_link as $key => $footer_link) {
                                if ($footer_link_data['FooterLink']['footer_category_id'] == $this->request->data['FooterLink']['footer_category_id']) {
                                    if ($footer_link_data['FooterLink']['position'] < $this->request->data['FooterLink']['position']) {
                                        $datas[$key]['position'] = $footer_link['FooterLink']['position'] - 1;
                                    } else {
                                        $datas[$key]['position'] = $footer_link['FooterLink']['position'] + 1;
                                    }
                                } else {
                                    $datas[$key]['position'] = $footer_link['FooterLink']['position'] + 1;
                                }
                                $datas[$key]['id'] = $footer_link['FooterLink']['id'];
                            }

                            $this->FooterLink->saveMany($datas, array('deep' => true));
                        }
                    }




                    $this->Session->setFlash(__('Footer link edited successfully'), 'default', array('class' => 'message success'), 'msg');
                    $this->redirect(array('action' => 'admin_add_footer_link', $footer_link_data['FooterLink']['footer_category_id'], 'admin' => true));
                } else {
                    $this->Session->setFlash(__('Sorry something went wrong.Please try again'), 'default', array('class' => 'message error'), 'msg');
                    $this->redirect(array('action' => 'admin_add_footer_link', $footer_link_data['FooterLink']['footer_category_id'], 'admin' => true));
                }
            } else {
                
            }
        } else {

            $this->request->data = $footer_link_data;
        }
    }

// deal active or deactive
    public function admin_list_footer_link() {
        $this->set('title_for_layout', 'All Footer Page');
        $this->loadModel('FooterLink');
        $this->FooterLink->bindModel(array('belongsTo' => array('FooterCategory' => array(
                    'foreignKey' => false,
                    'conditions' => array('FooterCategory.id=FooterLink.footer_category_id')
                )
            )
                )
        );
        $this->paginate = array(
            'limit' => 100,
            'conditions' => array(),
            'order' => array('FooterLink.id' => 'ASC'));
        $footer_all_page = $this->paginate('FooterLink');
        $this->set('footer_all_page', $footer_all_page);
    }

    public function admin_get_cat_total_link() {
        $this->autoLayout = false;
        if ($this->request->is('ajax')) {
            $this->loadModel('FooterLink');
            $cate_id = $this->request->data['current_cat'];
            $total_footer_link_count = $this->FooterLink->find('count', array('conditions' => array('FooterLink.footer_category_id' => $cate_id)));
            $position_array = array();
            if (isset($total_footer_link_count) && $total_footer_link_count > 0) {
                for ($count_position = 1; $count_position <= $total_footer_link_count + 1; $count_position++) {
                    $position_array[$count_position] = $count_position;
                }
            } else {
                $position_array[1] = 1;
            }
            echo json_encode($position_array);
            die;
        }
    }

// default photo
    public function admin_default_photo() {
        
    }

// default photo
    public function admin_default_photo_edit($photo_name) {
        if ($photo_name) {
            $this->set('photo_name', $photo_name);
            if ($this->request->is('post') || $this->request->is('put')) {
                $file_ext = @end(explode(".", $this->data['DefaultImage']['image']['name']));
                if ($file_ext == "png") {
                    if (isset($this->request->data) && !empty($this->request->data)) {
                        if ($this->data['DefaultImage']['image']['error'] == 0 && !empty($this->data['DefaultImage']['image']['name'])) {
                            $advertise_image_path = WWW_ROOT . 'data/' . $photo_name . '/' . $photo_name . "_default.png";
                            if (move_uploaded_file($this->data['DefaultImage']['image']['tmp_name'], $advertise_image_path)) {
                                $this->Session->setFlash(__('Default Image edited successfully'), 'default', array('class' => 'message success'), 'msg');
                                $this->redirect(array('action' => 'admin_default_photo', 'admin' => true));
                            }
                        }
                    }
                } else {
                    $this->Session->setFlash(__('Please upload .png format image'), 'default', array('class' => 'message success'), 'msg');
                }
            }
        }
    }
    // function for web setting
    public function admin_setting()
    {
        $this->loadModel('Setting');
        $data = $this->Setting->find("all", array('order' => 'Setting.id'));
        $this->set('data', $data);
        if ($this->request->is("post")) {
            $settings_posts = $this->request->data;
            foreach ($settings_posts["SiteSetting"] as $key => $value) {
                if ($value == "") {
                    $this->Session->setFlash(__("Please fill all the field"), 'default', array('class' => 'message error'), 'msg');
                    $this->redirect(array("controller" => "labs","action" => "setting"));
                }
            }
            foreach ($settings_posts["SiteSetting"] as $key => $value) {
                $value = addslashes($value);
                $this->Setting->create(false);
                $this->Setting->updateAll(array("Setting.value" => "'" . $value . "'"), array("Setting.slug" => $key));
            }
            $this->Session->setFlash(__("Site Settings have been saved successfully"), 'default', array('class' => 'message success'), 'msg');
            $this->redirect(array("controller" => "labs","action" => "setting"));
        }
    }
}

