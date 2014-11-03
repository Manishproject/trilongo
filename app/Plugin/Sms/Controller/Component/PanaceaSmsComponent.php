<?php
/**
 *
 * PHP versions 4 and 5
 *
 * This component is provided for the Panacea Mobile SMS Gateway
 * 
 * http://www.panaceamobile.com
 * 
 * Free SMS credits are available for testing.
 *
 * This component is licensed under The MIT License
 * 
 * written by Donald Jackson
 * http://www.ddj.co.za/
 *
 */

class PanaceaSmsComponent extends Object {

	/**
	 * Controller variable
	 *
	 * @var object
	 * @access protected
	 */

	var $Controller;

	/**
	 * Initialization variable
	 *
	 * @var boolean
	 * @access protected
	 */

	var $_init_done = false;

	/**
	 * Username for Panacea SMS Gateway
	 *
	 * @var string
	 * @access public
	 */
	var $username = null;

	/**
	 * Password for Panacea SMS Gateway
	 *
	 * @var string
	 * @access public
	 */
	var $password = null;

	/**
	 * Delivery report mask for Panacea SMS Gateway
	 *
	 * @var int
	 * @access public
	 */
	var $delivery_report_mask = 0;

	/**
	 * Default 'from' number
	 *
	 * @var string
	 * @access public
	 */

	var $default_from_number = "PanaceaSMS";

	/**
	 * Full URL to where DLR callbacks can be sent
	 *
	 * @var string
	 * @access public
	 */

	var $delivery_report_url = "";

	/**
	 * URL of the Panacea Mobile gateway
	 *
	 * @var string
	 * @access public
	 */

	var $api_url = "http://api.panaceamobile.com:23013/cgi-bin/sendsms?source=cake";
	
	/**
	 * Message storage path, if delivery reports are required (defaults to CakePHP Temp directory)
	 * 
	 * @var string
	 * 
	 * @access public
	 */
	
	var $message_storage_path = null;
	
	/**
	 * Automatically clean up DLR's when done
	 * 
	 * @var boolean
	 * @access public
	 */
	
	var $auto_cleanup = true;

	/**
	 * Startup component
	 *
	 * @param object $controller Instantiating controller
	 * @access public
	 */
	function initialize(&$Controller) {
		if(!function_exists('curl_init')) {
			$this->initializationError();
			return false;
		}
		$this->Controller =& $controller;

		$this->_init_done = true;

	}
	
	function Send($to, $message, $from = null) {
		if(is_array($to)) {
			$results = array();
			foreach($to as $number) {
				$results[] = $this->sendReal($number, $message, $from);
			}
			return $results;
		}
		return $this->sendReal($to, $message, $from);
	}

	/**
	 * Real Send SMS
	 * 
	 * @param string
	 * @param string
	 * @param string
	 * 
	 * @access protected
	 */

	function sendReal($to, $message, $from = null) {
		if($this->_init_done) {
			$error = false;
			$parameters = array(
				'username' => $this->username,
				'password' => $this->password,
				'to' => $to,
				'from' => is_null($from) ? $this->default_from_number : $from,
				'text' => $message
			);
			$required_parameters = array('username', 'password', 'text', 'from', 'to');
				
			foreach($required_parameters as $parameter) {
				if(empty($parameters[$parameter])) {
					trigger_error("Parameter {$parameter} is required");
					$error = true;
				}
			}
				
			if(!$error) {
				if($this->delivery_report_mask > 0) {
					/* DLR Tracking required */
					$parameters['dlr-mask'] = $this->delivery_report_mask;
					$delivery_report_url = $this->delivery_report_url;
					if(!empty($delivery_report_url)) {
						$message_id = $this->storeMessage(array('to' => $parameters['to'], 'from' => $parameters['from'], 'message' => $parameters['text']));
						if($message_id !== FALSE) {
							$sep = "?";
							if(strpos($delivery_report_url, "?") !== FALSE) {
								$sep = "&";
							}
							$delivery_report_url .= $sep."message_id=".$message_id."&status=%d";
							$parameters['dlr-url'] = $delivery_report_url;
						}
					}
				}
				$url = $this->api_url;
				foreach($parameters as $key => $value) {
					$url .= "&".urlencode($key)."=".urlencode($value);
				}
				

				$ch = curl_init($url);
				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$result = curl_exec($ch);

				$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
				if(($code >= 200) && ($code <= 400)) {
					/* Successfully sent */
					return true;
				}
			}
		} else {
			$this->initializationError();
		}
		return false;

	}

	/**
	 * Generic Initialization error
	 */

	function initializationError() {
		trigger_error('The Panacea SMS component requires curl to operate.');
	}

	/**
	 * Generate seemingly unique id
	 *
	 * @return string
	 */

	function generateMessageId() {
		return md5(md5(microtime(true).""));
	}

	/**
	 * Stores the message, so we can track its delivery later
	 *
	 * @param mixed
	 * @access protected
	 *
	 */

	function storeMessage($details) {
		if(is_null($this->message_storage_path)) {
			$this->message_storage_path = TMP;
		}

		$message_id = $this->generateMessageId();

		if(@file_put_contents($this->message_storage_path."dlr".$message_id, serialize($details))) {
			return $message_id;
		}

		return false;
	}

	/**
	 *
	 * Retrieves the message details for the delivery report
	 *
	 * @access public
	 *
	 * @return array Returns the status of this report and data
	 */

	function getDeliveryReport() {
		if(!empty($_GET['message_id']) && !empty($_GET['status'])) {
			if(is_null($this->message_storage_path)) {
				$this->message_storage_path = TMP;
			}
			
			$file_path = $this->message_storage_path."dlr".$_GET['message_id'];
			
			$details = @file_get_contents($file_path);
			if($details !== FALSE) {
				$result = unserialize($details);
				if($result !== FALSE) {
					$result['status'] = (int)$_GET['status'];
					$result['status_string'] = $this->statusToString($result['status']);
					if($this->auto_cleanup) {
						switch($result['status']) {
							case 1:
							case 2:
							case 16:
								unlink($file_path);
								break;
							default:
								/* No action */
								break;
						}
						
					}
					return $result;
				}
			}
				
		}
		return false;

	}
	
	/** 
	 * 
	 * Returns a string for the given status
	 * 
	 * @param int
	 * 
	 * @return string
	 * 
	 */
	
	function statusToString($status = null) {
		if(!is_null($status)) {
			$all_status = array(
				0 => 'Unknown',
				1 => 'Delivered',
				2 => 'Failed',
				4 => 'Buffered',
				8 =>  'Submitted to SMSC',
				16 => 'Failed at SMSC',
				32 => 'Intermediary update'
			);
			
			$status = (int)$status;
			
			if(isset($all_status[$status])) {
				return $all_status[$status];
			}
			
		}
		
		return "Unknown";
	}







}


?>
