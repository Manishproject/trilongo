<?php
/**
 * --- SMS Component ---
 * Github: https://github.com/steven/Cakephp-2.0.Component-SMS
 * Sends a SMS message to a phone number
 *
 * @author Steven Thompson <steven@fantasmagorical.co.uk>
 */
 
class TxtlocalSmsComponent extends Component {
	
	var $apiUrl = 'http://www.txtlocal.com/sendsmspost.php';
	var $username = 'YOURUSERNAME'; // Your txtlocal.com account username/email address
	var $password = 'YOURPASSWORD'; // Your txtlocal.com account password
	var $from = 'THE-DISPLAY-FROM-USER'; // e.g. your company name
	
	
	// If the test flag is set then no message will be sent but will display your in you txtlocal control panel
	function send($number, $message = 'This is a test message', $test = 0){
		
		$message = urlencode($message);
		
		// Prepare data for POST request
		$data = "uname=".$this->username."&pword=".$this->password."&message=".$message."&from=".$this->from."&selectednums=".$number."&info=1&test=".$test;
		
		// Send the POST request with cURL
		$ch = curl_init($this->apiUrl);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		if(curl_exec($ch)){ //This is the result from Txtlocal
			return true;
		} else {
			return false;		
		}
	}
}
