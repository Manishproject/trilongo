#CakePHP- Email Template beta 1.0

A basic implementation of a Email Template module.

##Requirements 
* CakePHP v2.x


##Installation
Unzip, clone or submodule the plugin into `app/Plugin/Mails`

  

/*****  Note : you need to edit change on view files for better UI for now remove all css and js from ctp file.
 for ajax search make sure you have included jquery 1.8 or higher
*****/



Enable admin prefix (default core.php) 
Configure::write('Routing.prefixes', array('admin'));
  
(default bootstrap.php)  
CakePlugin::load('Mails', array('routes' => true));


##Usage
-----------------
Create new email template :  /admin/mails/mails/new
Edit email template :  /admin/mails/mails/new/{id}
View all list of emails : /admin/mails/mails/index

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


Database table 

Table name : lab_emails ( prefix : "lab_" )


--
-- Table structure for table `lab_emails`
--

CREATE TABLE IF NOT EXISTS `lab_emails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(100) NOT NULL,
  `sender_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `subject` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `message` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`type`),
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ; 



Admin Layout 
-------------

    <body>
		<?php echo $this->element('admin_header');?>
		<?php echo $this->element('admin_menu'); ?>
		<?php echo $this->fetch('content'); ?>
		<?php echo $this->element('admin_footer'); ?>
	</body>


Error layout 
---------------
you can use "app/view/layout/404.ctp" layout for errors
$this->layout = '404';



