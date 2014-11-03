<?php
App::uses('EmailTemplateAppModel', 'EmailTemplate.Model');

class EmailTemplate extends EmailTemplateAppModel {
	

    public $actsAs = array('Containable');
	
		public $validate = array(
		'slug' => array( 'slug' => array('rule' => 'notEmpty','message' => 'Email type cannot be left blank.'),
                     'isUnique' => array('rule' => 'isUnique','message' => 'Tempalte Id  already in use.')),
		 'sender_name' => array('sender_name' => array('rule' => 'notEmpty','message' => 'Sender name cannot be left blank.',),
			    'pattern'=>array('rule'=> '/^[A-Za-z0-9 ]*$/','message'   => 'only alphaNumeric value allowed') ),
		
		'subject' => array('subject' => array('rule' => 'notEmpty','message' => 'Subject cannot be left blank.',),
			    'pattern'=>array('rule'=> '/^[A-Za-z0-9 ",.:;"?&()-_@!%+]*$/','message'   => 'only alphaNumeric value allowed') ),
		'email_from' => 'email',
        'message' => array('rule' => 'notEmpty','message' => 'This field cannot be left blank.',),
		'email_from' => array('email' => array('rule' => 'email','message' => 'please provide a valid email address.',),
	            'notEmpty' => array('rule' => 'notEmpty','message' => 'This field cannot be left blank.')),
    );
}