#CakePHP- Email Template beta 1.0

A basic implementation of a Email Template module.

##Requirements 
* CakePHP v2.x


##Installation
Unzip, clone or submodule the plugin into `app/Plugin/EmailTemplate`

  

/*****  Note : you need to edit change on view files for better UI for now remove all css and js from ctp file.
 for ajax search make sure you have included jquery 1.8 or higher
*****/



Enable admin prefix (default core.php) 
Configure::write('Routing.prefixes', array('admin'));
  
(default bootstrap.php)  
CakePlugin::load('EmailTemplate')

Import database tables from sql file.
##Usage
-----------------
Create new email template :  /admin/email_template/email_template/new
Edit email template :  /admin/email_template/email_template/new{id}
View all list of emails : /admin/email_template

Functionality 
--------------
1) Crate new email template
2) Edit 
3) Show all 
4) Search (Ajax)

constant (for website name)
----------------------------
/*defind in mail bootstrap.php  */
define('TITLE','Your website name here');
define('SITEURL','Domain name with /');


Use component:

public $components = array('EmailTemplate.Mailer');

   default settings
  $setting_array= array(
   'email_log'=>true, //True value store email as log into databsae
  'force_sent'=>true // This will send message instantly - make false for bulk emails in loop- so they can sent on cron.
  );
   
	$this->Mailer->sendByTemplateSlug($to,$TemplateId,$replacement_array,$setting_array); //For sending mail
	
	$this->Mailer->EmailLogs($to = null, $from = null, $sub = null, $body = null,$status= 0) ;// used for storing email into database
	
	$this->Mailer->CronSendEmails($limit_per_cron=50); // Use this into cron controller (Make sure you have added component before using these function)




