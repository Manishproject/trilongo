<?php
App::uses('Sanitize', 'Utility', 'AppController', 'Controller');
App::uses('EmailTemplateAppController', 'EmailTemplate.Controller');

class EmailTemplateController extends EmailTemplateAppController {

    var $components = array('Auth', 'Session','RequestHandler', 'Paginator');

	
   public function admin_index(){ 
  
   	   	$this->set('title_for_layout', 'All Email Templates');
	   	
	   	$a = array(1);
	    $this->paginate = array('recursive' =>-1,
            'limit' =>100,
            'order' => array('EmailTemplate.id' => 'DESC'));
        $data = $this->paginate('EmailTemplate');
      
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
		   			$data = $this->EmailTemplate->findById($id);
		   			 if(empty($data))
		   			 { $this->layout = '404'; } else{ $this->request->data = $data; }
	   			}
   		}
   		else{
	           if (!empty($this->request->data)) 
	           {
	           	
	            if ($this->EmailTemplate->save($this->request->data)) 
	            {   $lid = $this->EmailTemplate->getLastInsertId();
	            	$this->Session->setFlash('Email Template info has been save successfully', 'default', array('class' => 'message success'), 'msg');
	            	if(!empty($lid)) {
	                $this->redirect('/admin/email_template/email_template/new/'.$lid);
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
   
   public function admin_mail_search() {
   		$this->layout = false;
   		if(isset($this->data) && !empty($this->data))
   		{
   			$searchData =trim($this->data['mssg']);
   			$a = array(1);
   			$cond = array();
   			if(!empty($searchData)){
            $cond[] = array( 
                'or' => array(
                        "EmailTemplate.sender_name LIKE" => "%" . $searchData . "%",
                        "EmailTemplate.slug LIKE" => "%" . $searchData . "%",
                        "EmailTemplate.id" => $searchData,
                        "EmailTemplate.email_from LIKE" => "%" . $searchData . "%",
            			"EmailTemplate.message LIKE" => "%" . $searchData . "%",
                    ));
            }

            $this->paginate = array('recursive' => -1,
                'conditions' => $cond,
                'order' => array('EmailTemplate.id' => 'DESC'),
                'limit' => 100);
            $data = $this->paginate('EmailTemplate');
        $this->set('EmailTemplates', $data);
   		}
   }

}

