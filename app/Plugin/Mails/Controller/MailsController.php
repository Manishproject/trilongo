<?php
App::uses('Sanitize', 'Utility', 'AppController', 'Controller');
App::uses('MailsAppController', 'Mails.Controller');
class MailsController extends MailsAppController {

    public $uses = array('Mails.Mail');
    public $name = 'Mails';
    var $components = array('Auth', 'Session','RequestHandler', 'Paginator');
    public $helpers = array('Html', 'Form', 'JqueryEngine', 'Session', 'Text', 'Time','Paginator');

    function beforeFilter() 
    {
    	
    if(Configure::version() < 2 ){die('##Requirements * CakePHP v2.x');}
    	/* Get Controller's action name so you can make active class for menus or tab (prefix : "mail_" )   */
    	$links = "mail_".str_replace('admin_', '', $this->request->params['action']);
    	$this->set('acts',$links);
    	
    	AppController::beforeFilter();
    }
    

    /*
     * create new email template 
     */
   
   public function admin_index()
   {
   	   	$this->set('title_for_layout', 'All Email Templates');
	   	
	   	$a = array(1);
	    $this->paginate = array('recursive' =>-1,
            'limit' =>100,
            'order' => array('Mail.id' => 'DESC'));
        $data = $this->paginate('Mail');
        $this->set('all', $data);
   	
   }

   /*
     * Add / edit current email temaplate  
     */
   public function admin_new($id=null)
   {
   		$this->set('title_for_layout', 'Email Template');
   		
   		
   		if ($this->request->is('get')) 
   		{
	   			if(!empty($id))
	   			{
		   			$data = $this->Mail->findById($id);
		   			 if(empty($data))
		   			 { $this->layout = '404'; } else{ $this->request->data = $data; }
	   			}
   		}
   		else{
	           if (!empty($this->request->data)) 
	           {
	           	
	            if ($this->Mail->save($this->request->data)) 
	            {   $lid = $this->Mail->getLastInsertId();
	            	$this->Session->setFlash('Email Template info has been save successfully', 'default', array('class' => 'message success'), 'msg');
	            	if(!empty($lid)) {
	                $this->redirect('/admin/mails/mails/new/'.$lid);
	            	}else{
	            		$this->redirect($this->referer());
	            	}
	                
	            } else 
	            {
	                $this->Session->setFlash('Not able to save', 'default', array('class' => 'message error'), 'msg');
	            }
	        }
   		}
   }
   
   public function admin_mail_search()
   {
   		$this->layout = false;
   		if(isset($this->data) && !empty($this->data))
   		{
   			$searchData =trim($this->data['mssg']);
   			$a = array(1);
   			$cond = array();
   			if(!empty($searchData)){
            $cond[] = array( 
                'or' => array(
                        "Mail.sender_name LIKE" => "%" . $searchData . "%",
                        "Mail.type LIKE" => "%" . $searchData . "%",
                        "Mail.id" => $searchData,
                        "Mail.email LIKE" => "%" . $searchData . "%",
            			"Mail.message LIKE" => "%" . $searchData . "%",
                    ));
            }

            $this->paginate = array('recursive' => -1,
                'conditions' => $cond,
                'order' => array('Mail.id' => 'DESC'),
                'limit' => 100);
            $data = $this->paginate('Mail');
        $this->set('all', $data);
   		}
   }

}

