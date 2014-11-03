<?php
App::uses('Component', 'Controller');

class MailerComponent extends Component{

	private $_defaultSettings = array('email_log'=>true,'force_sent'=>true);
	public $components=array('Email','Session');
	public $smtp = false;

	public function __construct(ComponentCollection $collection, $settings = array()){
		parent::__construct($collection, $settings);
		$this->settings = array_merge($this->_defaultSettings, $settings);
	}

	/**
	 *
	 * Enter description here ...
	 * @param  $to - User id,
	 * @param  $templateSlug
	 * @param  $keywords
	 */
	public function sendByTemplateSlug($to,$templateSlug,$keywords = array(),$settings=array()){

		$settings = array_merge($this->settings, $settings);


		if(empty($templateSlug) && empty($to)){
			return false;
		}

		if(is_numeric($to)){
			$User= ClassRegistry::init('User');
			$User->id = $to;
			$Userdata->recursive = -1;
			$Userdata= $User->findById($to);
			$to = $Userdata['User']['email'];
			$keywords['[user:name]']= $Userdata['User']['username'];
		}
		if(!filter_var($to,FILTER_VALIDATE_EMAIL)){
			$this->Session->setFlash(__('Could not send email.'), 'default');
			return  FALSE ; // Not valid email found
		}

		$EmailTemplateModel = ClassRegistry::init('EmailTemplate');

		$TemplateFetch = $EmailTemplateModel->findBySlug($templateSlug);
		if(empty($TemplateFetch)){
			$this->Session->setFlash(__('Invalid Email Template.'), 'default');
			return false;
		}

		$from    = isset($settings['from'])?$settings['from']:$TemplateFetch['EmailTemplate']['email_from'];
		$subject = $TemplateFetch['EmailTemplate']['subject'];
		$msg     = $TemplateFetch['EmailTemplate']['message'];

		/*Replace Keyword with it's value*/
		foreach((array)$keywords as $key => $val){
			$msg = str_replace($key,$val,$msg);
			$subject = str_replace($key,$val,$subject);
		}

		$this->Email->subject = $subject;
		$this->Email->to =$to;
		$this->Email->from =$from;
		$this->Email->sendAs='both';

		try {
			if($settings['force_sent']){
			 if ($this->Email->send($msg)) {
			 	if($settings['email_log']){
			 		$this->EmailLogs($to,$from,$subject,$msg,1);// store as sent success
			 	}
			 	return true;
			 }else {

			 	$this->EmailLogs($to,$from,$subject,$msg,0);// store as unsent

			 	$this->Session->setFlash(__('Could not send email.'), 'default');
			 	return false;
			 }
			 	
			}else{
				$this->EmailLogs($to,$from,$subject,$msg,0);// store as unsent- we will sent it again on cron.
				return true;
			}


		} catch (Exception $e) {
			$this->EmailLogs($to,$from,$subject,$msg,0);// store as unsent
			$this->Session->setFlash(__('Could not send email.'), 'default');
 	     return false;
		}
  return false;
	}

	public function EmailLogs($to = null, $from = null, $sub = null, $body = null,$status= 0) {
		$msg = 0;
		$today_year = date("Y");

		$body = str_replace('[CYEAR]', $today_year, $body);

		if (!empty($to) && !empty($from)) {
			$emails = ClassRegistry::init('EmailLog');
			$emails->create();
			$emails->set('email_to', $to);
			$emails->set('email_from', $from);
			$emails->set('subject', $sub);
			$emails->set('message', $body);
			$emails->set('status', $status);
			$emails->save(null, false);
			$msg = 1;
		}
		return $msg;
	}

	/**
	 * 
	 * this function can be Used on cron controller to sends emails from email logs which has sent status = 0;
	 * @param int $limit
	 */
	public function CronSendEmails($limit=50){
		$emails = ClassRegistry::init('EmailLog');
		$logs = $emails->find('all',array('conditions'=>array('status'=>0),'limit'=>$limit));
			
		foreach ($logs as $log){

		$this->Email->subject = $log['EmailLog']['subject'];
		$this->Email->to =$log['EmailLog']['email_to'];
		$this->Email->from =$log['EmailLog']['email_from'];
		$this->Email->sendAs='both';

			try {
				$this->Email->send($log['EmailLog']['message']);
				$log['EmailLog']['status']=1;
				$emails->save($log);
			} catch (Exception $e) {
				
			}
		}

	}
	
	public function sendMail($to,$from,$subject,$msg,$force_sent =1){
				
		$this->Email->subject = $subject;
		$this->Email->to =$to;
		$this->Email->from =$from;
		$this->Email->sendAs='both';

		try {
			if($force_sent){
			 if ($this->Email->send($msg)) {
			 	$this->EmailLogs($to,$from,$subject,$msg,1);// store as sent success
			 	return true;
			 }else {
			 	$this->EmailLogs($to,$from,$subject,$msg,0);// store as unsent
			 	$this->Session->setFlash(__('Could not send email.'), 'default');
			 	return false;
			 }
			 	
			}else{
				$this->EmailLogs($to,$from,$subject,$msg,0);// store as unsent- we will sent it again on cron.
				return true;
			}


		} catch (Exception $e) {
			
			$this->EmailLogs($to,$from,$subject,$msg,0);// store as unsent
			$this->Session->setFlash(__('Could not send email.'), 'default');
 	     return false;
		}
	
	}
}
