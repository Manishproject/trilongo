<?php
App::uses('PagesAppModel', 'Pages.Model');

class Page extends PagesAppModel {
	
    public $actsAs = array('Containable');

    public $validate = array(
			'title' => array('rule' => 'notEmpty','message' => 'This field cannot be left blank.'),
    		'url' => array(
				'notEmpty' => array('rule' => array('notEmpty'),'allowEmpty' => false,'message' => 'Please enter url'),
				 'isUnique' => array('rule' => 'isUnique','message' => 'url already exists.'),
    	 		'pattern'=>array('rule'=> '/^[A-Za-z0-9_-]*$/','message'   => 'Only letters,number,underscore and hyphen allowed')),
				 
    		);
    		
		public function beforesave($str=null)
		{
  			if(!empty($this->data['Page']['title']))
  				{
					$this->data['Page']['title']= trim($this->data['Page']['title']);
	  			}
	  			
	  		if(!empty($this->data['Page']['url']))
  				{
  					$this->data['Page']['url'] = trim($this->data['Page']['url']);
  					$this->data['Page']['url'] =strtolower(Inflector::slug($this->data['Page']['url'], '-'));
					$this->data['Page']['url']= $this->data['Page']['url'] ;
	  			}
	  		}
	  		

}