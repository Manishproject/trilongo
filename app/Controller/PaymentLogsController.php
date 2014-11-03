<?php

App::uses('AppController', 'Controller');

class PaymentLogsController extends AppController {

	public function beforeFilter(){
	parent::beforeFilter();
	
	}
	
	public function admin_index(){
	
		
   	   	$this->set('title_for_layout', 'Payment Logs');
	   	
	    $condition =array();
	    $this->PaymentLog->bindModel(array('belongsTo'=>array('User')));
		if(isset($this->request->data['search'])&& !empty($this->request->data['search'])){
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
            'order' => array('PaymentLog.id' => 'DESC')
		);
		
	    $data = $this->paginate('PaymentLog');
   
        $this->set('all', $data);
        
	}
	
}
