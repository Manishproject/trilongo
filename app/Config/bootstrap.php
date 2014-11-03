<?php
/**
 * This file is loaded automatically by the app/webroot/index.php file after core.php
 *
 * This file should load/create any application wide configuration settings, such as
 * Caching, Logging, loading additional configuration files.
 *
 * You should also use this file to include any files that provide global functions/constants
 * that your application uses.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.10.8.2117
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

// Setup a 'default' cache configuration for use in the application.
Cache::config('default', array('engine' => 'File'));

/**
 * The settings below can be used to set additional paths to models, views and controllers.
 *
 * App::build(array(
 *     'Model'                     => array('/path/to/models/', '/next/path/to/models/'),
 *     'Model/Behavior'            => array('/path/to/behaviors/', '/next/path/to/behaviors/'),
 *     'Model/Datasource'          => array('/path/to/datasources/', '/next/path/to/datasources/'),
 *     'Model/Datasource/Database' => array('/path/to/databases/', '/next/path/to/database/'),
 *     'Model/Datasource/Session'  => array('/path/to/sessions/', '/next/path/to/sessions/'),
 *     'Controller'                => array('/path/to/controllers/', '/next/path/to/controllers/'),
 *     'Controller/Component'      => array('/path/to/components/', '/next/path/to/components/'),
 *     'Controller/Component/Auth' => array('/path/to/auths/', '/next/path/to/auths/'),
 *     'Controller/Component/Acl'  => array('/path/to/acls/', '/next/path/to/acls/'),
 *     'View'                      => array('/path/to/views/', '/next/path/to/views/'),
 *     'View/Helper'               => array('/path/to/helpers/', '/next/path/to/helpers/'),
 *     'Console'                   => array('/path/to/consoles/', '/next/path/to/consoles/'),
 *     'Console/Command'           => array('/path/to/commands/', '/next/path/to/commands/'),
 *     'Console/Command/Task'      => array('/path/to/tasks/', '/next/path/to/tasks/'),
 *     'Lib'                       => array('/path/to/libs/', '/next/path/to/libs/'),
 *     'Locale'                    => array('/path/to/locales/', '/next/path/to/locales/'),
 *     'Vendor'                    => array('/path/to/vendors/', '/next/path/to/vendors/'),
 *     'Plugin'                    => array('/path/to/plugins/', '/next/path/to/plugins/'),
 * ));
 *
 */

/**
 * Custom Inflector rules can be set to correctly pluralize or singularize table, model, controller names or whatever other
 * string is passed to the inflection functions
 *
 * Inflector::rules('singular', array('rules' => array(), 'irregular' => array(), 'uninflected' => array()));
 * Inflector::rules('plural', array('rules' => array(), 'irregular' => array(), 'uninflected' => array()));
 *
 */

/**
 * Plugins need to be loaded manually, you can either load them one by one or all of them in a single call
 * Uncomment one of the lines below, as you need. Make sure you read the documentation on CakePlugin to use more
 * advanced ways of loading plugins
 *
 * CakePlugin::loadAll(); // Loads all plugins at once
 * CakePlugin::load('DebugKit'); //Loads a single plugin named DebugKit
 *
 */
//CakePlugin::load('DebugKit');
CakePlugin::load('EmailTemplate');
CakePlugin::load('Taxonomy');
CakePlugin::load('Mails');
CakePlugin::load('Pages');
//CakePlugin::loadAll();
/**
 * You can attach event listeners to the request lifecycle as Dispatcher Filter. By default CakePHP bundles two filters:
 *
 * - AssetDispatcher filter will serve your asset files (css, images, js, etc) from your themes and plugins
 * - CacheDispatcher filter will read the Cache.check configure variable and try to serve cached content generated from controllers
 *
 * Feel free to remove or add filters as you see fit for your application. A few examples:
 *
 * Configure::write('Dispatcher.filters', array(
 *		'MyCacheFilter', //  will use MyCacheFilter class from the Routing/Filter package in your app.
 *		'MyCacheFilter' => array('prefix' => 'my_cache_'), //  will use MyCacheFilter class from the Routing/Filter package in your app with settings array.
 *		'MyPlugin.MyFilter', // will use MyFilter class from the Routing/Filter package in MyPlugin plugin.
 *		array('callable' => $aFunction, 'on' => 'before', 'priority' => 9), // A valid PHP callback type to be called on beforeDispatch
 *		array('callable' => $anotherMethod, 'on' => 'after'), // A valid PHP callback type to be called on afterDispatch
 *
 * ));
 */
Configure::write('Dispatcher.filters', array(
	'AssetDispatcher',
	'CacheDispatcher'
));

/**
 * Configures default file logging options
 */
App::uses('CakeLog', 'Log');
CakeLog::config('debug', array(
	'engine' => 'File',
	'types' => array('notice', 'info', 'debug'),
	'file' => 'debug',
));
CakeLog::config('error', array(
	'engine' => 'File',
	'types' => array('warning', 'error', 'critical', 'alert', 'emergency'),
	'file' => 'error',
));

//$SITEURL  = 'http://www.trilongo.com/';
$SITEURL  =  'http://192.168.1.53/trilongo/';
$SITEURL  =  'http://192.168.1.53/trilongo/';
define('SITEURL', $SITEURL);
define('SITE_URL', $SITEURL);
define('SITE_TITLE', 'trilongo');
define('ADMIN_ROLE', '1');
define('GUEST_ROLE', '2');
define('AGENT_ROLE', '3');
define('TODAYDATE', date('y-m-d H:i:s'));
define('C_URL', 'abc');
define('ADMIN_EMAIL', 'getfreelancework@gmail.com');
date_default_timezone_set('Asia/Kolkata');
$server_host=$_SERVER['HTTP_HOST'];
$sub_server_host=explode('.',$server_host);
$sub_server_host=$sub_server_host[0];
if($sub_server_host==192 || $sub_server_host=="localhost")  //If local
{
    $web_url='http://'.$server_host."/trilongo/";
    //Defining paypal credentials constant//
 //Defining paypal credentials constant//
    define("PAYPAL_USERNAME", "vineet.kumar-facilitator_api1.deemtech.com");
    define("PAYPAL_PASSWORD", "1373970737");
    define("PAYPAL_SIGNATURE", "A9vWK8UcxEbbjvJN.jaiKMxqZ47KA5gq3pVfneR0iRk0AtZrAr1n8jMJ");
    define("PAYPAL_SANDBOX", TRUE);

}
else    //If live
{
    $web_url="http://".$server_host."/";
    //Defining paypal credentials constant//
    define("PAYPAL_USERNAME", "bradp90_api1.gmail.com");
    define("PAYPAL_PASSWORD", "HUFDFRZXEYCKD6J9");
    define("PAYPAL_SIGNATURE", "AFcWxV21C7fd0v3bYYYRCpSSRl31AQqdKKUSkXfKS0Qx7jm2EwPQbUDZ");
    define("PAYPAL_SANDBOX", FALSE);
}
define('TRILONGO_PER_FEE', '0.15'); // Percentage
define('TRILONGO_FIXED_FEE', '10'); // Percentage
define('BOARD_CAST_MILE', '500'); // Percentage
define('LIMIT_PAGINATION', '10'); // limitation per page
//payment divide before reservation
define('PROVIDER_FUND_BEFORE_RESERVATION', '50');
define("MY_IP", "192.168.1.53");
header("Expires: Thu, 19 Nov 1981 08:52:00 GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");



//define("TWILIO_ADMIN_NO","870-494-9121");
//define("TWILIO_ACCOUNT_SID","AC51875af985b263515fb8990d96e9a07b");
//define("TWILIO_AUTH_TOKEN","47a1eb10f40b97fde9600c585712c451");

define("TWILIO_ADMIN_NO","508-232-6600");
define("TWILIO_ACCOUNT_SID","AC6085ad8744b94df301e9790d53c7ff8a");
define("TWILIO_AUTH_TOKEN","81fee1c719888cb51d1eff6ed0f91270");



