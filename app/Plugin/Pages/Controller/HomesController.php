<?php

//App::uses('Sanitize', 'Utility', 'AppController', 'Controller');
App::uses('PagesAppController', 'Pages.Controller');

class HomesController extends PagesAppController {

    public $uses = array('Pages.Page');
    var $components = array('Auth', 'Session', 'RequestHandler', 'Paginator');
    public $helpers = array('Html', 'Form', 'JqueryEngine', 'Session', 'Text', 'Time', 'Paginator');

    function beforeFilter() {

        if (Configure::version() < 2) {
            die('##Requirements * CakePHP v2.x');
        }
        parent::beforeFilter();
        $this->Auth->allow('index');
    }

    public function index($url = null) {
        $this->layout = 'default';

        if (!empty($url)) {

            $page_data = $this->Page->find('first', array('conditions' => array('Page.url' => $url, 'Page.status' => 1)));
            if (!empty($page_data)) {
                $this->set('title_for_layout', $page_data['Page']['title']);
                $this->set('page', $page_data);
                $page_meta = array('des' => $page_data['Page']['description'], 'key' => $page_data['Page']['keywords']);
                $this->set('page_meta', $page_meta);
            } else {
                $this->layout = "home_404";
            }
        } else {
            $this->layout = "home_404";
        }
    }

    /*
     * create new email template 
     */

    public function admin_index() {
        $this->set('title_for_layout', 'All Page');

        $this->paginate = array('recursive' => -1,
            'limit' => 100,
            'order' => array('Page.id' => 'ASC'));
        $data = $this->paginate('Page');
        $this->set('all', $data);
    }

    /*
     * Add / edit current email temaplate  
     */

    public function admin_new($id = null) {
        $this->set('title_for_layout', 'Page Template');


        if ($this->request->is('get')) {
            if (!empty($id)) {
                $data = $this->Page->findById($id);
                if (empty($data)) {
                    $this->layout = '404';
                } else {
                    $this->request->data = $data;
                }
            }
        } else {
            if (!empty($this->request->data)) {

                if ($this->Page->save($this->request->data)) {
                    if (isset($this->request->data['Page']['id']) && !empty($this->request->data['Page']['id'])) {
                        $lid = $this->request->data['Page']['id'];
                    } else {
                        $lid = $this->Page->getLastInsertId();
                    }

                    $msg = '<div class="alert alert-success"><button data-dismiss="alert" class="close"></button><strong>Success!</strong> The page has been saved.</div>';
                    $this->Session->setFlash($msg, 'default', array('class' => 'new'), 'msg');
                    $this->redirect('/admin/pages/homes/new/' . $lid);
                } else {
                    $this->Session->setFlash('<div class="alert alert-error"><button data-dismiss="alert" class="close"></button><strong>Error!</strong> Not able to save</div>', 'default', array('class' => 'emp'), 'msg');
                }
            }
        }
    }

    /* for custome search */

    public function admin_search_page() {
        $this->layout = false;
        if (isset($this->data) && !empty($this->data)) {

            $searchData = trim($this->data['mssg']);
            $cond = array();
            if (!empty($searchData)) {
                $cond[] = array(
                    'or' => array(
                        "Page.title LIKE" => "%" . $searchData . "%",
                        "Page.id" => $searchData,
                    //"Page.views" => $searchData,
                ));
            }

            $this->paginate = array('recursive' => -1,
                'conditions' => $cond,
                'order' => array('Page.id' => 'DESC'),
                'limit' => 100);
            $data = $this->paginate('Page');
            $this->set('all', $data);
        }
    }

    public function admin_updated_status() {
        $this->autoRender = false;
        if (isset($this->data) && !empty($this->data)) {
            $date = DATE;
            $id = trim($this->data['mssg']);
            $data = $this->Page->find('first', array('conditions' => array('Page.id' => $id), 'fields' => array('Page.id', 'Page.status')));
            if (!empty($data)) {
                if ($data['Page']['status'] == 1) {
                    $st = 0;
                } elseif ($data['Page']['status'] == 0) {
                    $st = 1;
                }

                if ($this->Page->updateAll(array('Page.status' => $st, 'Page.updated' => "'$date'"), array('Page.id' => $id))) {
                    if ($st == 0) {
                        echo "Draft";
                    } elseif ($st == 1) {
                        echo "Published";
                    }
                }
            }
        }
    }

}

