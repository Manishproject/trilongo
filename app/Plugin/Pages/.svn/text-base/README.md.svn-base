#CakePHP- static page module beta 1.0

A basic implementation of a static page module.

##Requirements 
* CakePHP v2.x


##Installation
Unzip, clone or submodule the plugin into `app/Plugin/Pages`

  

/*****  Note : you need to edit change on view files for better UI for now remove all css and js from ctp file.
 for ajax search make sure you have included jquery 1.8 or higher
*****/



Enable admin prefix (default core.php) 
Configure::write('Routing.prefixes', array('admin'));
  
(default bootstrap.php)  
CakePlugin::load('Pages', array('routes' => true));


##Usage
-----------------
Create new email template :  /admin/pages/homes/new
Edit email template :  /admin/pages/homes/new/{id}
View all list of emails : /admin/pages/homes/index

Functionality 
--------------
1) Crate new static page
2) Edit 
3) Show all 
4) Search (Ajax)

constant (for website name)
----------------------------
/*defind in mail bootstrap.php  */
define("DATE", date("Y-m-d H:i:s"));



Database table 

Table name : lab_emails ( prefix : "lab_" )



--
-- Table structure for table `lab_pages`
--

CREATE TABLE IF NOT EXISTS `lab_pages` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `post_data` longtext NOT NULL,
  `description` text,
  `keywords` text,
  `views` bigint(20) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `status` int(2) NOT NULL DEFAULT '1' COMMENT '0: draft , 1: public',
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `url` (`url`),
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;



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



