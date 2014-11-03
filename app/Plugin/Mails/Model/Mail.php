<?php
App::uses('MailsAppModel', 'Mails.Model');

class Mail extends MailsAppModel {
	
	public $useTable = 'mails';
	public $name = 'Email';
    public $actsAs = array('Containable');
	
		public $validate = array(
		'type' => array( 'type' => array('rule' => 'notEmpty','message' => 'Email type cannot be left blank.'),
            'alphaNumeric' => array('rule' => 'alphaNumeric','message' => 'only alphaNumeric.'),
            'isUnique' => array('rule' => 'isUnique','message' => 'Email type already in use.')),
		//'sender_name' =>array('rule' => 'notEmpty','message' => 'This field cannot be left blank.',),
		
		 'sender_name' => array('sender_name' => array('rule' => 'notEmpty','message' => 'Sender name cannot be left blank.',),
			    'pattern'=>array('rule'=> '/^[A-Za-z0-9 ]*$/','message'   => 'only alphaNumeric value allowed') ),
		
		'subject' => array('subject' => array('rule' => 'notEmpty','message' => 'Subject cannot be left blank.',),
			    'pattern'=>array('rule'=> '/^[A-Za-z0-9 ",.:;"?&()-_@!%+]*$/','message'   => 'only alphaNumeric value allowed') ),
		'email' => 'email',
        'message' => array('rule' => 'notEmpty','message' => 'This field cannot be left blank.',),
		'email' => array('email' => array('rule' => 'email','message' => 'please provide a valid email address.',),
	            'notEmpty' => array('rule' => 'notEmpty','message' => 'This field cannot be left blank.')),
    );
}